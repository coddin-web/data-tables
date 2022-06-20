<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

final class TextField extends FormInput
{
    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_WYSIWYG = 'wysiwyg';

    public function __construct(
        string $label,
        string $name,
        string $type = self::TYPE_TEXT,
        string $helpText = null,
    ) {
        parent::__construct(
            label: $label,
            name: $name,
            type: $type,
            helpText: $helpText,
        );
    }
}
