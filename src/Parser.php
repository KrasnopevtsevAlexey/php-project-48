<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

/**
 * Парсит контент в объект в зависимости от формата.
 */
function parse(string $content, string $format): object
{
    switch (strtolower($format)) {
        case 'json':
            $data = json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Исправлено: RuntimeException вместо \Exception
                throw new \RuntimeException("Invalid JSON: " . json_last_error_msg());
            }
            return $data;
        case 'yaml':
        case 'yml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            // Исправлено: InvalidArgumentException вместо \Exception
            throw new \InvalidArgumentException("Unsupported format: {$format}");
    }
}

/**
 * Читает файл по пути, возвращая контент и его формат.
 */
function readFileData(string $filepath): array
{
    if (!file_exists($filepath)) {
        // Исправлено: RuntimeException вместо \Exception
        throw new \RuntimeException("File not found: {$filepath}");
    }

    $content = file_get_contents($filepath);
    if ($content === false) {
        // Исправлено: RuntimeException вместо \Exception
        throw new \RuntimeException("Cannot read file: {$filepath}");
    }

    $format = pathinfo($filepath, PATHINFO_EXTENSION);

    return [$content, $format];
}
