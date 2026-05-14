<?php

namespace Hexlet\Code\Formatters\Plain;

/**
 * Приводит значение к строковому представлению в соответствии с правилами формата plain.
 */
function stringify(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string) $value;
}

/**
 * Рендерит AST-дерево в формате plain.
 */
function render(array $ast, string $ancestry = ''): string
{
    $lines = array_map(function ($node) use ($ancestry) {
        // Формируем полный путь к текущему свойству через точку
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
                // Неизмененные свойства в формате plain опускаются
                return null;
            default:
                throw new \Exception("Unknown node type: {$node['type']}");
        }
    }, $ast);

    // Удаляем null-элементы (unchanged) и склеиваем строки через перевод строки
    return implode("\n", array_filter($lines));
}
