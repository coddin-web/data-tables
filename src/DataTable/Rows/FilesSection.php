<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Rows;

use App\Models\File;
use App\Services\ArrayAble\ArrayHelper;

final class FilesSection extends DataSection
{
    public const DISPLAY_SECTION_TYPE_FILES = 'files';

    /**
     * @param File[] $files
     */
    public function __construct(
        protected string $name,
        protected ?string $description,
        protected string $filesRoute,
        protected string $fileStoreRoute,
        protected array $files = [],
        protected string $displaySectionType = self::DISPLAY_SECTION_TYPE_FILES,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            actions: [],
            displaySectionType: $displaySectionType,
        );
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
            'filesRoute' => $this->filesRoute,
            'fileStoreRoute' => $this->fileStoreRoute,
            'files' => $this->files,
            'displaySectionType' => $this->displaySectionType,
        ];
    }
}
