<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\render as renderStylish;
use function Differ\Formatters\Plain\render as renderPlain;
use function Differ\Formatters\Json\render as renderJson;

function format(array $diffTree, string $formatName): string
{
    return match ($formatName) {
        'stylish' => renderStylish($diffTree),
        'plain' => renderPlain($diffTree),
        'json' => renderJson($diffTree),
        // Исправлено: InvalidArgumentException вместо \Exception
        default => throw new \InvalidArgumentException("Unknown format: '{$formatName}'")
    };
}
