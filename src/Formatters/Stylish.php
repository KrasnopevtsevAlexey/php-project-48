<?php

namespace Differ\Formatters\Stylish;

use InvalidArgumentException;

function render(array $diffTree, int $depth = 1): string
{
    $indent = str_repeat(' ', $depth * 4 - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($node) use ($depth, $indent) {
        $key = $node['key'];

        return match ($node['type']) {
            'nested' => sprintf(
                "%s  %s: %s",
                $indent,
                $key,
                render($node['children'], $depth + 1)
            ),
            'unchanged' => sprintf(
                "%s  %s: %s",
                $indent,
                $key,
                stringify($node['value'], $depth + 1)
            ),
            'added' => sprintf(
                "%s+ %s: %s",
                $indent,
                $key,
                stringify($node['value'], $depth + 1)
            ),
            'deleted' => sprintf(
                "%s- %s: %s",
                $indent,
                $key,
                stringify($node['value'], $depth + 1)
            ),
            'changed' => sprintf(
                "%s- %s: %s\n%s+ %s: %s",
                $indent,
                $key,
                stringify($node['oldValue'], $depth + 1),
                $indent,
                $key,
                stringify($node['newValue'], $depth + 1)
            ),
            default => throw new InvalidArgumentException("Unknown node type: {$node['type']}")
        };
    }, $diffTree);

    return sprintf("{\n%s\n%s}", implode("\n", $lines), $bracketIndent);
}
function stringify(mixed $value, int $depth): string
{
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        $value === null => 'null',
        default => (function () use ($value, $depth) {
            $obj = is_object($value) ? get_object_vars($value) : $value;
            if (!is_array($obj)) {
                return (string) $value;
            }

            $indent = str_repeat(' ', $depth * 4);
            $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

            $lines = array_map(function ($key, $val) use ($depth, $indent) {
                return sprintf("%s%s: %s", $indent, $key, stringify($val, $depth + 1));
            }, array_keys($obj), $obj);

            return sprintf("{\n%s\n%s}", implode("\n", $lines), $bracketIndent);
        })()
    };
}
