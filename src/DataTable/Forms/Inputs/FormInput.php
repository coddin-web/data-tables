<?php

declare(strict_types=1);

namespace Coddin\DataTables\DataTable\Forms\Inputs;

use Coddin\DataTables\DataTable\Forms\InputOption;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ViewErrorBag;

abstract class FormInput implements FormInputInterface
{
    /** @var array<int, mixed>|callable|null */
    private $valueGetter = null;

    /**
     * FormInput constructor.
     * @param string $label
     * @param string $name
     * @param string $type
     * @param string|null $customType
     * @param string|null $helpText
     * @param array<InputOption|array<mixed>> $options
     * @param callable|null $valueGetter
     * @param string|int|bool|DateTimeInterface|array<int, mixed>|null $rawValue
     */
    public function __construct(
        private string $label,
        private string $name,
        private string $type,
        private ?string $customType = null,
        private ?string $helpText = null,
        private array $options = [],
        ?callable $valueGetter = null,
        private string|int|bool|DateTimeInterface|null|array $rawValue = null,
    ) {
        if (is_callable($valueGetter)) {
            $this->valueGetter = $valueGetter;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(?Model $model): array
    {
        return [
            'label' => $this->label,
            'helpText' => $this->helpText,
            'customType' => $this->customType,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->getValue($model),
            'errors' => $this->getErrors(),
            'options' => $this->getOptions(),
        ];
    }

    /**
     * @param Model|null $model
     * @return string|int|bool|DateTimeInterface|array<int, mixed>|null
     */
    private function getValue(?Model $model = null): string|int|bool|DateTimeInterface|null|array
    {
        if ($this->rawValue) {
            return $this->rawValue;
        }

        if ($model === null) {
            return null;
        }

        if (is_callable($this->valueGetter)) {
            return call_user_func_array(
                $this->valueGetter,
                [
                    $model,
                    $this->name,
                ],
            );
        }

        if (str_contains($this->name, '.')) {
            $parts = explode('.', $this->name);
            $field = $parts[(count($parts) - 1)];
            $target = $model;

            for ($i = 0; $i < count($parts); $i++) {
                if (($i + 1) === count($parts)) {
                    break;
                }

                $target = $target->{$parts[$i]};
            }

            if (!isset($target->{$field})) {
                return null;
            }

            if (is_a($target->{$field}, DateTimeInterface::class)) {
                return $target->{$field}->format('d-m-Y H:i');
            }

            return $target->{$field};
        }

        if (isset($model->{$this->name})) {
            if (is_a($model->{$this->name}, DateTimeInterface::class)) {
                return $model->{$this->name}->format('d-m-Y H:i');
            }

            return $model->{$this->name};
        }

        return null;
    }

    /**
     * @return array<string>
     */
    private function getErrors(): array
    {
        if (!Session::has('errors')) {
            return [];
        }

        /** @var ViewErrorBag $errorBag */
        $errorBag = Session::get('errors');

        return $errorBag->get($this->name);
    }

    /**
     * @return mixed[]
     */
    protected function getOptions(): array
    {
        $formattedOptions = [];

        foreach ($this->options as $option) {
            if (!is_array($option)) {
                $formattedOptions[] = $option->toArray();
            } else {
                $formattedOptions[] = $option;
            }
        }

        return $formattedOptions;
    }
}
