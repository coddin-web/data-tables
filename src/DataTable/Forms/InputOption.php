<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms;

use Illuminate\Contracts\Support\Arrayable;

final class InputOption implements Arrayable
{
    public function __construct(
        private null|int|string $value,
        private string $text,
        private bool $disabled = false,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'text' => $this->text,
            'disabled' => $this->disabled,
        ];
    }
}
