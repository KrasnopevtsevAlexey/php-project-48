<?php

namespace Differ\Differ;

use function Differ\Parser\readFileData;
use function Differ\Parser\parse;
use function Differ\Formatters\format;
use function Funct\Collection\sortBy;

/**
 * Главная функция библиотеки (находится первой).
 */
function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    [$content1, $format1] = readFileData($pathToFile1);
    [$content2, $format2] = readFileData($pathToFile2);

    $obj1 = parse($content1, $format1);
    $obj2 = parse($content2, $format2);

    $data1 = get_object_vars($obj1);
    $data2 = get_object_vars($obj2);

    $diffTree = makeDiffTree($data1, $data2);

    return format($diffTree, $formatName);
}

/**
 * Строит внутреннее дерево различий.
 */
function makeDiffTree(array $data1, array $data2): array
{
    // Используем array_unique для надежного сбора уникальных ключей
    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sortBy($allKeys, fn($key) => $key);

    return array_map(function ($key) use ($data1, $data2) {
        $exists1 = array_key_exists($key, $data1);
        $exists2 = array_key_exists($key, $data2);

        if ($exists1 && !$exists2) {
            return [
                'key' => $key,
                'type' => 'deleted',
                'value' => $data1[$key]
            ];
        }
        if (!$exists1 && $exists2) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $data2[$key]
            ];
        }

        $val1 = $data1[$key];
        $val2 = $data2[$key];

        if (is_object($val1) && is_object($val2)) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => makeDiffTree(get_object_vars($val1), get_object_vars($val2))
            ];
        }

        if ($val1 === $val2) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $val1
            ];
        }

        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $val1,
            'newValue' => $val2
        ];
    }, $sortedKeys);
}
