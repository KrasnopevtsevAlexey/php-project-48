<?php

namespace Hexlet\Code\Differ;

use function Hexlet\Code\Parser\parseFile;
use function Hexlet\Code\Formatters\Stylish\render;

function buildAst(array $data1, array $data2): array
{
    $allKeys = array_keys(array_merge($data1, $data2));
    $sortedKeys = \Funct\Collection\sortBy($allKeys, fn($key) => $key);

    return array_map(function ($key) use ($data1, $data2) {
        $exists1 = array_key_exists($key, $data1);
        $exists2 = array_key_exists($key, $data2);

        if ($exists1 && !$exists2) {
            return ['key' => $key, 'type' => 'deleted', 'value' => $data1[$key]];
        }
        if (!$exists1 && $exists2) {
            return ['key' => $key, 'type' => 'added', 'value' => $data2[$key]];
        }
        if (is_array($data1[$key]) && is_array($data2[$key])) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildAst($data1[$key], $data2[$key])
            ];
        }
        if ($data1[$key] === $data2[$key]) {
            return ['key' => $key, 'type' => 'unchanged', 'value' => $data1[$key]];
        }

        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $data1[$key],
            'newValue' => $data2[$key]
        ];
    }, $sortedKeys);
}


function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    
    $data1 = json_decode(json_encode(parseFile($pathToFile1)), true);
    $data2 = json_decode(json_encode(parseFile($pathToFile2)), true);

    $ast = buildAst($data1, $data2);

    if ($format === 'stylish') {
        return render($ast);
    }

    throw new \Exception("Unknown format: {$format}");
}
