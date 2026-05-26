<?php

namespace Differ\Differ;

use function Differ\Parser\readFileData;
use function Differ\Parser\parse;
use function Differ\Formatters\format;
use function Funct\Collection\sortBy;


function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    [$content1, $format1] = readFileData($pathToFile1);
    [$content2, $format2] = readFileData($pathToFile2);

    $obj1 = parse($content1, $format1);
    $obj2 = parse($content2, $format2);

   
    $diffTree = makeDiffTree($obj1, $obj2);

    return format($diffTree, $formatName);
}

function makeDiffTree(object $obj1, object $obj2): array
{
    
    $data1 = get_object_vars($obj1);
    $data2 = get_object_vars($obj2);

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sortBy($allKeys, fn($key) => $key);

    return array_map(function ($key) use ($data1, $data2) {
        $exists1 = array_key_exists($key, $data1);
        $exists2 = array_key_exists($key, $data2);

        return match (true) {
            $exists1 && !$exists2 => [
                'key' => $key,
                'type' => 'deleted',
                'value' => $data1[$key]
            ],
            !$exists1 && $exists2 => [
                'key' => $key,
                'type' => 'added',
                'value' => $data2[$key]
            ],
            is_object($data1[$key]) && is_object($data2[$key]) => [
                'key' => $key,
                'type' => 'nested',
                // Рекурсивно передаем вложенные объекты как есть
                'children' => makeDiffTree($data1[$key], $data2[$key])
            ],
            $data1[$key] === $data2[$key] => [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $data1[$key]
            ],
            default => [
                'key' => $key,
                'type' => 'changed',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key]
            ]
        };
    }, $sortedKeys);
}
