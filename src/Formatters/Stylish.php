<?php

namespace Hexlet\Code\Formatters\Stylish;

/**
 * Преобразует любое значение в строку в стиле stylish.
 */
function stringify(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (!is_array($value)) {
        return (string) $value;
    }

    $indent = str_repeat(' ', $depth * 4);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($key, $val) use ($depth, $indent) {
        $nestedValue = stringify($val, $depth + 1);
        return "{$indent}{$key}: {$nestedValue}";
    }, array_keys($value), $value);

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}

/**
 * Рендерит AST-дерево в строку.
 */
function render(array $ast, int $depth = 1): string
{
    $indent = str_repeat(' ', $depth * 4 - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($node) use ($depth, $indent) {
        $key = $node['key'];
        switch ($node['type']) {
            case 'nested':
                $nestedRender = render($node['children'], $depth + 1);
                return "{$indent}  {$key}: {$nestedRender}";
            case 'unchanged':
                $val = stringify($node['value'], $depth + 1);
                return "{$indent}  {$key}: {$val}";
            case 'added':
                $val = stringify($node['value'], $depth + 1);
                return "{$indent}+ {$key}: {$val}";
            case 'deleted':
                $val = stringify($node['value'], $depth + 1);
                return "{$indent}- {$key}: {$val}";
            case 'changed':
                $oldVal = stringify($node['oldValue'], $depth + 1);
                $newVal = stringify($node['newValue'], $depth + 1);
                return "{$indent}- {$key}: {$oldVal}\n" .
                       "{$indent}+ {$key}: {$newVal}";
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $ast);

    return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
}
