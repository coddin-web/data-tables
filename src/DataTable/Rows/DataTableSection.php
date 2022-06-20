<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Rows;

use Coddin\DataTables\ArrayAble\ArrayHelper;
use Coddin\DataTables\DataTable\Table\DataTable;

final class DataTableSection extends DataSection
{
    public function __construct(
        string $name,
        ?string $description,
        private DataTable $dataTable,
        array $actions = [],
        string $displaySectionType = self::DISPLAY_SECTION_TYPE_DATA_TABLE,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            actions: $actions,
            displaySectionType: $displaySectionType,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'actions' => ArrayHelper::toArray(...$this->actions),
            'dataTable' => $this->dataTable->toArray(),
            'displaySectionType' => $this->displaySectionType,
        ];
    }
}
