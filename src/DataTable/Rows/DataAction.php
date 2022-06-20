<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Rows;

use Illuminate\Contracts\Support\Arrayable;

final class DataAction implements Arrayable
{
    /**
     * @param string $route
     * @param array<string, mixed>|array<int, mixed> $params
     * @param string $label
     * @param bool $global
     * @param string $method
     * @param string $icon
     */
    public function __construct(
        private string $label,
        private string $route,
        private array $params,
        private bool $global = false,
        private string $method = '',
        private string $icon = '',
    ) {
        if ($global && !empty($params)) {
            throw new \LogicException('Unable to create global action with parameters');
        }
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'route' => $this->route,
            'params' => $this->params,
            'global' => $this->global,
            'method' => $this->method,
            'icon' => $this->icon,
        ];
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
