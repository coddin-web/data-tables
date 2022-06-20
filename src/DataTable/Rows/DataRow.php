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
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'displayType' => $this->displayType,
        ];
    }

    /*
     * Easy accessors
     */
    public static function text(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_TEXT);
    }

    public static function textArea(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_TEXTAREA);
    }

    public static function boolean(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_BOOLEAN);
    }

    public static function badge(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_BADGE);
    }

    public static function file(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_FILE);
    }

    public static function contact(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_CONTACT);
    }

    public static function address(string $label, mixed $value): self
    {
        return new self($label, $value, self::DISPLAY_TYPE_ADDRESS);
    }
}
