<?php

namespace App\Differ;

use Exception;

/**
 * @throws Exception
 */
function getContentJson($filePath)
{
    $absolutePath = realpath($filePath);
    if ($absolutePath === false) {
        throw new Exception("File '{$filePath}' does not exist");
    }
    $content = file_get_contents($absolutePath);
    if (false === $content) {
        throw new Exception("Unable to read file '{$filePath}'.");
    }
    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON in file '{$filePath}'.");
    }
    ksort($data);
    return $data;
}

function compareFiles(array $firstData, array $secondData): array
{
    $diff = [];
    foreach ($firstData as $key => $value) {
        $firstValue = formatValue($value);
        if (array_key_exists($key, $secondData)) {
            $secondValue = $secondData[$key];
            $formattedSecondValue = formatValue($secondValue);
            if ($value == $secondValue) {
                $diff[] = "  {$key}: {$firstValue}";
            } else {
                $diff[] = "- {$key}: {$firstValue}";
                $diff[] = "+ {$key}: {$formattedSecondValue}";
            }
        } else {
            $diff[] = "- {$key}: {$firstValue}";
        }
    }
    foreach ($secondData as $subKey => $subValue) {
        if (!array_key_exists($subKey, $firstData)) {
            $secondSubValue = formatValue($subValue);
            $diff[] = "+ {$subKey}: {$secondSubValue}";
        }
    }
    return $diff;
}

function formatValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return (string)$value;
}

/**
 * @throws Exception
 */
function genDiff($filePath1, $filePath2): string
{
    $firstData = getContentJson($filePath1);
    $secondData = getContentJson($filePath2);
    $diff = compareFiles($firstData, $secondData);
    $lines = array_map(fn($line) => "  {$line}", $diff);
    return "{\n" . implode("\n", $lines) . "\n}";
}
