<?php

namespace Hexlet\Code\Formatters\Json;

function render(array $ast): string
{
    // JSON_PRETTY_PRINT делает вывод читаемым для человека (с отступами)
    // JSON_UNESCAPED_UNICODE сохраняет кириллицу и спецсимволы без экранирования
    return (string) json_encode($ast, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
