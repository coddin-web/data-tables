<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

final class Form implements Arrayable
{
    /**
     * @param  string  $action
     * @param  string  $method
     * @param  FormSection[]  $sections
     * @param  Model|null  $model
     */
    public function __construct(
        private string $action,
        private string $method = 'POST',
        private array $sections = [],
        private ?Model $model = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'action' => $this->action,
            'method' => $this->method,
            'sections' => $this->formatSections(),
        ];
    }

    /**
     * @return array<mixed>
     */
    private function formatSections(): array
    {
        $sections = [];

        foreach ($this->sections as $section) {
            $sections[] = $section->toArray($this->model);
        }

        return $sections;
    }
}
