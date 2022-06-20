<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

final class FilesField extends FormInput
{
    /**
     * @param string $label
     * @param string $name
     * @param string|null $customType
     * @param string|null $helpText
     */
    public function __construct(
        string $label,
        string $name,
        ?string $customType = null,
        ?string $helpText = null,
    ) {
        parent::__construct($label, $name, 'files', $customType, $helpText);
    }
}
