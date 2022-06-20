<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

use Illuminate\Database\Eloquent\Model;

interface FormInputInterface
{
    /**
     * @param Model|null $model
     * @return array<string, mixed>
     */
    public function toArray(?Model $model): array;
}
