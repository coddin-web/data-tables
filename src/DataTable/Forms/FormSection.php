<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms;

use Coddin\DataTables\DataTable\Forms\Inputs\FormInputInterface;
use Illuminate\Database\Eloquent\Model;

final class FormSection
{
    /**
     * @param  string  $name
     * @param  string  $description
     * @param  FormInputInterface[]  $inputs
     */
    public function __construct(
        private string $name = '',
        private string $description = '',
        private array $inputs = [],
    ) {
    }

    /**
     * @param  Model|null  $model
     * @return array<string, mixed>
     */
    public function toArray(?Model $model): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'inputs' => $this->formatFormInputs($model),
        ];
    }

    /**
     * @param  Model|null  $model
     * @return array<mixed>
     */
    private function formatFormInputs(?Model $model): array
    {
        $formInputs = [];

        foreach ($this->inputs as $input) {
            $formInputs[] = $input->toArray($model);
        }

        return $formInputs;
    }
}
