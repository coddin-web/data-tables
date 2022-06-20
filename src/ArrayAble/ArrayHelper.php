<?php

declare(strict_types=1);

namespace Coddin\DataTables\ArrayAble;

use Illuminate\Contracts\Support\Arrayable;

final class ArrayHelper
{
    /**
     * @param Arrayable ...$array
     * @return array<mixed>
     */
    public static function toArray(Arrayable ...$array): array
    {
        $formattedArray = [];

        /** @var mixed $arrayItem */
        foreach ($array as $arrayItem) {
            if (!is_array($arrayItem)) {
                $formattedArray[] = $arrayItem->toArray();
            } else {
                $formattedArray[] = $arrayItem;
            }
        }

        return $formattedArray;
    }
}
