<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\render as renderStylish;
use function Hexlet\Code\Formatters\Plain\render as renderPlain;
use function Hexlet\Code\Formatters\Json\render as renderJson;

function format(array $ast, string $format): string
{
    switch ($format) {
        case 'stylish':
            return renderStylish($ast);
        case 'plain':
            return renderPlain($ast);
        case 'json':
            return renderJson($ast);
        default:
            throw new \Exception("Unknown format: '{$format}'. Supported formats: stylish, plain.");
    }
}
