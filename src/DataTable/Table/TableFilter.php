<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Table;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;

final class TableFilter implements Arrayable
{
    private mixed $currentValue = null;

    /**
     * @param array<int, string>|array<string, string> $values
     */
    public function __construct(
        private string $label,
        private string $key,
        private array $values,
        private Closure $query,
    ) {
    }

    public function toArray()
    {
        return [
            'label' => $this->label,
            'key' => $this->key,
            'values' => $this->values,
            'current' => $this->currentValue,
        ];
    }

    public function apply(Builder $builder, mixed $value): void
    {
        $this->currentValue = $value;
        call_user_func_array($this->query, [$builder, $value]);
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
