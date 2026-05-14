<?php

namespace Hexlet\Code\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $format): \stdClass
{
    switch (strtolower($format)) {
        case 'json':
            $data = json_decode($content);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON: " . json_last_error_msg());
            }
            return $data;

        case 'yaml':
        case 'yml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);

        default:
            throw new \Exception("Unsupported format: {$format}");
    }
}

function parseFile(string $filepath): \stdClass
{
    if (!file_exists($filepath)) {
        throw new \Exception("File not found: {$filepath}");
    }

    $content = file_get_contents($filepath);

    

    if ($content === false) {
        throw new \Exception("Cannot read file: {$filepath}");
    }
    $format = pathinfo($filepath, PATHINFO_EXTENSION);
    return parse($content, $format);
}
