<?php

namespace Differ\Formatters\Plain;

use function is_array;
use function is_object;
use function is_bool;
use function is_string;
use function implode;
use function array_map;
use function array_filter;
use function sprintf;

/**
 * Рендерит AST-дерево в формате plain.
 */
function render(array $diffTree, string $ancestry = ''): string
{
    $lines = array_map(function ($node) use ($ancestry) {
        $property = $ancestry === '' ? $node['key'] : "{$ancestry}.{$node['key']}";

        // match вычисляет строку и возвращает её ОДИН раз для всего узла
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

    // Удаляем null (unchanged свойства) и склеиваем строки
    return implode("\n", array_filter($lines));
}

/**
 * Приводит значение к строковому представлению по правилам формата plain.
 * Вспомогательная функция размещена внизу файла, как просил ментор.
 */
function stringify(mixed $value): string
{
    // Всего один оператор return для всей функции! Ошибка S1142 исчезнет.
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        $value === null => 'null',
        is_array($value) || is_object($value) => '[complex value]',
        is_string($value) => "'{$value}'",
        default => (string) $value
    };
}
