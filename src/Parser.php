<?php

namespace Hexlet\Code;

function parseFile(string $filepath): object
{
    if (!file_exists($filepath)) {
        throw new \Exception("File not found: {$filepath}");
    }

    $content = file_get_contents($filepath);

    if ($content === false) {
        throw new \Exception("Cannot read file: {$filepath}");
    }

    $data = json_decode($content);

    if ($data === null) {
        throw new \Exception("Invalid JSON in file: {$filepath}");
    }

    return $data;
}
