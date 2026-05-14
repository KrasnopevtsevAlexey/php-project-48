<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\render as renderStylish;
use function Hexlet\Code\Formatters\Plain\render as renderPlain;

/**
 * Фабрика для выбора нужного формата вывода диффа.
 *
 * @param array $ast Промежуточное дерево отличий
 * @param string $format Название формата (stylish, plain)
 * @return string
 * @throws \Exception
 */
function format(array $ast, string $format): string
{
    switch ($format) {
        case 'stylish':
            return renderStylish($ast);
        case 'plain':
            return renderPlain($ast);
        default:
            throw new \Exception("Unknown format: '{$format}'. Supported formats: stylish, plain.");
    }
}
