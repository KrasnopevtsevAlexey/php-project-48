<?php

namespace Differ\Formatters\Json;

function render(array $diffTree): string
{
    return json_encode($diffTree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
}
