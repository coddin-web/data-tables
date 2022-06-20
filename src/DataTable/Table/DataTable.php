<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Table;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DataTable implements Arrayable
{
    /**
     * @param array<TableColumn> $columns
     * @param array<TableAction> $actions
     * @param array<TableFilter> $filters
     */
    public function __construct(
        private Builder|Relation $builder,
        private Closure $transform,
        private array $columns,
        private array $actions,
        private ?Closure $export = null,
        private array $filters = [],
    ) {
        $this->searchQuery();
        $this->sortQuery();
        $this->archiveQuery();
        $this->filterQuery();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        /** @var AbstractCursorPaginator $paginator */
        $paginator = $this->builder
            ->paginate(10)
            ->appends(
                request()->only(['search', 'sortBy', 'sortDir', 'archive', 'filter']),
            )->appends(
                collect(request()->all())
                ->filter(fn($value, $key) => str_starts_with($key, 'filter'))
                ->toArray(),
            );

        $paginator->getCollection()->transform($this->transform);

        return [
            'paginator' => $paginator,
            'search' => request()->get('search'),
            'sortBy' => $this->getSortingColumn(),
            'sortDir' => $this->getSortingDirection(),

            'columns' => collect($this->columns)->transform(fn(TableColumn $column) => $column->__toArray()),
            'actions' => collect($this->actions)
                ->filter(fn(TableAction $action) => auth()->user()?->can($action->getRoute()))
                ->values(),
            'filters' => collect($this->filters)
                ->values(),
            'hasArchive' => $this->hasArchive(),
            'canExport' => $this->export !== null,
        ];
    }

    private function searchQuery(): void
    {
        $searchQuery = (string) request()->get('search');
        if ($searchQuery === '') {
            return;
        }

        $this->builder->where(
            function (Builder $builder) use ($searchQuery) {
                foreach ($this->getSearchableColumns() as $column) {
                    switch ($column->getDisplayComponent()) {
                        case TableColumn::DISPLAY_COMPONENT_ID:
                            // @todo find out why this isn't working
                            $builder->orWhere($column->getSortName(), (int) $searchQuery);
                            break;
                        case TableColumn::DISPLAY_COMPONENT_TEXT:
                            $builder->orWhere($column->getSortName(), 'LIKE', str_replace('%', '', $searchQuery) . '%');
                            break;
                    }
                }
            },
        );
    }

    private function sortQuery(): void
    {
        $sortingColumn = $this->getSortingColumn();

        if (!str_contains($sortingColumn, '.')) {
            $sortingColumn = $this->builder->getModel()->getTable() . '.' . $sortingColumn;
        }

        $this->builder->orderBy(
            $sortingColumn,
            $this->getSortingDirection(),
        );
    }

    private function getSortingDirection(): string
    {
        $direction = request()->get('sortDir');

        if (!in_array($direction, ['asc', 'desc'])) {
            return 'asc';
        }

        return $direction;
    }

    /**
     * @return Collection<TableColumn>
     */
    private function getSearchableColumns(): Collection
    {
        return collect($this->columns)
            ->filter(fn(TableColumn $column) => $column->isSearchable());
    }

    /**
     * @return Collection<TableColumn>
     */
    private function getSortableColumnNames(): Collection
    {
        return collect($this->columns)
            ->filter(fn(TableColumn $column) => $column->isSortable())
            ->transform(fn(TableColumn $column) => $column->getSortName());
    }

    private function getSortingColumn(): string
    {
        if (request()->missing('sortBy')) {
            return 'id';
        }

        $sortBy = request()->get('sortBy');

        if (!$this->getSortableColumnNames()->contains($sortBy)) {
            return 'id';
        }

        return $sortBy;
    }

    private function hasArchive(): bool
    {
        return $this->builder->getModel() instanceof HasArchive;
    }

    private function archiveQuery(): void
    {
        if (!$this->hasArchive()) {
            return;
        }

        $showArchive = request()->has('archive') && request()->get('archive') === '1';

        if ($showArchive) {
            $this->builder->whereNotNull('archived_at');
        } else {
            $this->builder->whereNull('archived_at');
        }
    }

    private function filterQuery(): void
    {
        /** @var TableFilter $filter */
        foreach ($this->filters as $filter) {
            $filterValue = request()->get('filter_' . $filter->getKey());
            if ($filterValue) {
                $filter->apply($this->builder, $filterValue);
            }
        }
    }

    public function export(string $exportName): StreamedResponse
    {
        $data = $this->builder
            ->get()
            ->transform($this->export);

        return response()->streamDownload(
            function () use ($data) {
                if ($data->isEmpty()) {
                    return;
                }

                echo implode(';', array_keys($data->first())) . "\r\n";

                foreach ($data as $row) {
                    if ($row) {
                        echo implode(';', $row) . "\r\n";
                    }
                }
            },
            $exportName . '.csv',
        );
    }
}
