<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Table;

use Illuminate\Contracts\Support\Arrayable;

final class TableAction implements Arrayable
{
    /**
     * @param array<int|string, int|string> $params
     */
    public function __construct(
        private string $label,
        private string $route,
        private array $params,
        private bool $global = false,
        private string $method = '',
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'route' => $this->route,
            'params' => $this->params,
            'global' => $this->global,
            'method' => $this->method,
        ];
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
