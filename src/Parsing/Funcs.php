<?php

namespace Diff\Parsing;

function getContentJson($fileName)
{
    $filePath = __DIR__ . '/../Files/';
    $file = $filePath . $fileName;
    if (!file_exists($file)) {
        throw new \Exception("File '{$file}' does not exist.");
    }
    $content = file_get_contents($file);
    if (false === $content) {
        throw new \Exception("Unable to read file '{$file}'.");
    }
    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid JSON in file '{$file}'.");
    }
    return $data;
}
