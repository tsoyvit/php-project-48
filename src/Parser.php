<?php

namespace Differ\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @throws Exception
 */

function parseFile(string $filepath): object
{
    $content = file_get_contents($filepath);
    if ($content === false) {
        throw new Exception('Unable to read file: ' . $filepath);
    }
    $extension = pathinfo($filepath, PATHINFO_EXTENSION);

    return match ($extension) {
        'json' => json_decode($content),
        'yaml', 'yml' => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new \Exception("Unsupported file format: {$extension}"),
    };
}
