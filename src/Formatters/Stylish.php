<?php

namespace Differ\Formatters\Stylish;

function render(array $diffTree, int $depth = 1): string
{
    $indent = str_repeat(' ', $depth * 4 - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($node) use ($depth, $indent) {
        $key = $node['key'];
        switch ($node['type']) {
            case 'nested':
                $nestedRender = render($node['children'], $depth + 1);
                return sprintf("%s  %s: %s", $indent, $key, $nestedRender);
            case 'unchanged':
                $val = stringify($node['value'], $depth + 1);
                return sprintf("%s  %s: %s", $indent, $key, $val);
            case 'added':
                $val = stringify($node['value'], $depth + 1);
                return sprintf("%s+ %s: %s", $indent, $key, $val);
            case 'deleted':
                $val = stringify($node['value'], $depth + 1);
                return sprintf("%s- %s: %s", $indent, $key, $val);
            case 'changed':
                $oldVal = stringify($node['oldValue'], $depth + 1);
                $newVal = stringify($node['newValue'], $depth + 1);
                return sprintf("%s- %s: %s\n%s+ %s: %s", $indent, $key, $oldVal, $indent, $key, $newVal);
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $diffTree);

    return sprintf("{\n%s\n%s}", implode("\n", $lines), $bracketIndent);
}

function stringify(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }

    $obj = is_object($value) ? get_object_vars($value) : $value;
    if (!is_array($obj)) {
        return (string) $value;
    }

    $indent = str_repeat(' ', $depth * 4);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($key, $val) use ($depth, $indent) {
        $nestedValue = stringify($val, $depth + 1);
        return sprintf("%s%s: %s", $indent, $key, $nestedValue);
    }, array_keys($obj), $obj);

    return sprintf("{\n%s\n%s}", implode("\n", $lines), $bracketIndent);
}
