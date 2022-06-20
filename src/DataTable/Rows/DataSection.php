<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Rows;

use Coddin\DataTables\ArrayAble\ArrayHelper;
use Illuminate\Contracts\Support\Arrayable;

class DataSection implements Arrayable
{
    public const DISPLAY_SECTION_TYPE_DEFAULT = 'default';
    public const DISPLAY_SECTION_TYPE_ADDRESS = 'address';
    public const DISPLAY_SECTION_TYPE_DATA_TABLE = 'dataTable';
    public const DISPLAY_SECTION_TYPE_FILES = 'files';

    /**
     * @param DataAction[] $actions
     * @param DataRow[] $rows
     */
    public function __construct(
        protected string $name,
        protected ?string $description,
        protected array $actions = [],
        protected array $rows = [],
        protected string $displaySectionType = self::DISPLAY_SECTION_TYPE_DEFAULT,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'actions' => ArrayHelper::toArray(...$this->actions),
            'rows' => ArrayHelper::toArray(...$this->rows),
            'displaySectionType' => $this->displaySectionType,
        ];
    }
}
