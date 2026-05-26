<?php

namespace Differ\Formatters\Plain;

function render(array $diffTree, string $ancestry = ''): string
{
    $lines = array_map(function ($node) use ($ancestry) {
        $property = $ancestry === '' ? $node['key'] : "{$ancestry}.{$node['key']}";

        return match ($node['type']) {
            'nested' => render($node['children'], $property),
            'added' => sprintf(
                "Property '%s' was added with value: %s",
                $property,
                stringify($node['value'])
            ),
            'deleted' => sprintf(
                "Property '%s' was removed",
                $property
            ),
            'changed' => sprintf(
                "Property '%s' was updated. From %s to %s",
                $property,
                stringify($node['oldValue']),
                stringify($node['newValue'])
            ),
            'unchanged' => null,
            default => throw new \InvalidArgumentException("Unknown node type: {$node['type']}")
        };
    }, $diffTree);

    return implode("\n", array_filter($lines));
}

function stringify(mixed $value): string
{
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        $value === null => 'null',
        is_array($value) || is_object($value) => '[complex value]',
        is_string($value) => "'{$value}'",
        default => (string) $value
    };
}
