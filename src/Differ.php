<?php

namespace Hexlet\Code\Differ;

use function Hexlet\Code\parseFile;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $data1 = (array) parseFile($pathToFile1);
    $data2 = (array) parseFile($pathToFile2);

    $allKeys = array_keys(array_merge($data1, $data2));

    $sortKeys = \Funct\Collection\sortBy($allKeys, function ($key) {
        return $key;
    });

    $diffLines = array_map(function ($key) use ($data1, $data2) {
        $exists1 = array_key_exists($key, $data1);
        $exists2 = array_key_exists($key, $data2);

        if ($exists1 && !$exists2) {
            return "  - {$key}: " . stringify($data1[$key]);
        }

        if (!$exists1 && $exists2) {
            return "  + {$key}: " . stringify($data2[$key]);
        }

        if ($data1[$key] === $data2[$key]) {
            return "    {$key}: " . stringify($data1[$key]);
        }

        return "  - {$key}: " . stringify($data1[$key]) . "\n" .
               "  + {$key}: " . stringify($data2[$key]);
    }, $sortKeys);

    return "{\n" . implode("\n", $diffLines) . "\n}";
}

function stringify($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return (string) $value;
}
