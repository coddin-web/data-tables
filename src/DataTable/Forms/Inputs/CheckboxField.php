<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

final class CheckboxField extends FormInput
{
    public function __construct(
        string $label,
        string $name,
        ?string $helpText = null,
        ?callable $valueGetter = null,
    ) {
        parent::__construct(
            label: $label,
            name: $name,
            type: 'checkbox',
            helpText: $helpText,
            valueGetter: $valueGetter,
        );
    }
}
