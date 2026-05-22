<?php

namespace Differ\Formatters\Plain;

function render(array $diffTree, string $ancestry = ''): string
{
    $lines = array_map(function ($node) use ($ancestry) {
        $property = $ancestry === '' ? $node['key'] : "{$ancestry}.{$node['key']}";

        switch ($node['type']) {
            case 'nested':
                return render($node['children'], $property);
            case 'added':
                $val = stringify($node['value']);
                return "Property '{$property}' was added with value: {$val}";
            case 'deleted':
                return "Property '{$property}' was removed";
            case 'changed':
                $oldVal = stringify($node['oldValue']);
                $newVal = stringify($node['newValue']);
                return "Property '{$property}' was updated. From {$oldVal} to {$newVal}";
            case 'unchanged':
                return null;
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $diffTree);

    return implode("\n", array_filter($lines));
}

function stringify(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string) $value;
}
