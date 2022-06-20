<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Rows;

use Illuminate\Contracts\Support\Arrayable;

final class DataRow implements Arrayable
{
    const DISPLAY_TYPE_TEXT = 'text';
    const DISPLAY_TYPE_TEXTAREA = 'textarea';
    const DISPLAY_TYPE_BOOLEAN = 'boolean';
    const DISPLAY_TYPE_BADGE = 'badge';
    const DISPLAY_TYPE_FILE = 'file';
    const DISPLAY_TYPE_CONTACT = 'contact';
    const DISPLAY_TYPE_ADDRESS = 'address';

    /**
     * @param  string  $label
     * @param  mixed  $value
     * @param  string  $displayType
     */
    public function __construct(
        private string $label,
        private mixed $value,
        private string $displayType = self::DISPLAY_TYPE_TEXT,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'displayType' => $this->displayType,
        ];
    }
}
