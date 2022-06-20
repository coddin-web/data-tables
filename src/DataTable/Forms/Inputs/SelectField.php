<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

use Coddin\DataTables\DataTable\Forms\InputOption;
use DateTimeInterface;

final class SelectField extends FormInput
{
    /**
     * @param string $label
     * @param string $name
     * @param array<int, InputOption> $options
     * @param string|null $customType
     * @param string|null $helpText
     */
    public function __construct(
        string $label,
        string $name,
        array $options,
        ?string $customType = null,
        ?string $helpText = null,
        ?callable $valueGetter = null,
        string|int|bool|DateTimeInterface|null $rawValue = null,
    ) {
        parent::__construct($label, $name, 'select', $customType, $helpText, $options, $valueGetter, $rawValue);
    }
}
