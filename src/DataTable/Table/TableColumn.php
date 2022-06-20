<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Table;

use LogicException;

final class TableColumn
{
    public const DISPLAY_COMPONENT_ID = 'id';
    public const DISPLAY_COMPONENT_ID_CHECKBOX = 'checkbox';
    public const DISPLAY_COMPONENT_TEXT = 'text';
    public const DISPLAY_COMPONENT_BOOLEAN = 'boolean';
    public const DISPLAY_COMPONENT_BADGE = 'badge';
    public const DISPLAY_COMPONENT_PROFILE = 'profile';

    public function __construct(
        private string $name,
        private string $translationTag,
        private bool $searchable = false,
        private bool $sortable = true,
        private string $displayComponent = self::DISPLAY_COMPONENT_TEXT,
        private ?string $sortName = null,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function __toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->getTranslation($this->translationTag),
            'searchable' => $this->searchable,
            'sortable' => $this->sortable,
            'sortName' => $this->getSortName(),
            'displayComponent' => $this->displayComponent,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSortName(): string
    {
        return ($this->sortName ?? $this->getName());
    }

    public function getDisplayComponent(): string
    {
        return $this->displayComponent;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    private function getTranslation(string $translationTag): string
    {
        $translation = __($translationTag);

        if (is_array($translation) || $translation === null) {
            throw new LogicException('Translation should be a string');
        }

        return $translation;
    }
}
