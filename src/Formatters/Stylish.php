<?php

namespace Differ\Formatters\Stylish;

use InvalidArgumentException;

use function is_bool;
use function is_array;
use function is_object;
use function get_object_vars;
use function str_repeat;
use function array_map;
use function array_keys;
use function implode;
use function sprintf;

/**
 * Рендерит дерево различий в формате stylish.
 */
function render(array $diffTree, int $depth = 1): string
{
    $indent = str_repeat(' ', $depth * 4 - 2);
    $bracketIndent = str_repeat(' ', ($depth - 1) * 4);

    $lines = array_map(function ($node) use ($depth, $indent) {
        $key = $node['key'];

        // Всего один return для всего замыкания! Ошибка S1142 исчезнет.
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
            // Исправлено: специализированное исключение по требованию ментора и SonarQube
            default => throw new InvalidArgumentException("Unknown node type: {$node['type']}")
        };
    }, $diffTree);

    return sprintf("{\n%s\n%s}", implode("\n", $lines), $bracketIndent);
}

/**
 * Приводит вложенные данные-значения к строковому виду по правилам stylish.
 * Вспомогательная функция размещена внизу файла, как просил ментор.
 */
function stringify(mixed $value, int $depth): string
{
    // Всего один return для всей функции! Ошибка S1142 исчезнет.
    return match (true) {
        is_bool($value) => $value ? 'true' : 'false',
        $value === null => 'null', // Исправлено на строгое сравнение === null (Hint PHP7103)
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
