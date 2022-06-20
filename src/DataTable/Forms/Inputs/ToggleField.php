<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

class ToggleField extends FormInput
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
            type: 'toggle',
            helpText: $helpText,
            valueGetter: $valueGetter,
        );
    }
}
