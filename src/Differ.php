<?php

namespace App\Differ;

use Exception;

use function App\Parser\parseFile;
use function App\Formatters\format;

/**
 * @throws Exception
 */
function genDiff(string $filepath1, string $filepath2, string $format = 'stylish'): string
{
    $file1Data = parseFile($filepath1);
    $file2Data = parseFile($filepath2);
    $diff = buildDiff($file1Data, $file2Data);
    return format($diff, $format);
}

function buildDiff(object $file1Data, object $file2Data): array
{
    $keys1 = array_keys(get_object_vars($file1Data));
    $keys2 = array_keys(get_object_vars($file2Data));
    $allKeys = array_unique(array_merge($keys1, $keys2));
    sort($allKeys);
    $diff = [];
    foreach ($allKeys as $key) {
        $hasKey1 = property_exists($file1Data, $key);
        $hasKey2 = property_exists($file2Data, $key);


        if ($hasKey1 && !$hasKey2) {
            $diff[] = ['type' => 'removed', 'key' => $key, 'value' => $file1Data->$key];
        } elseif (!$hasKey1 && $hasKey2) {
            $diff[] = ['type' => 'added', 'key' => $key, 'value' => $file2Data->$key];
        } elseif ($hasKey1 && $hasKey2) {
            $value1 = $file1Data->$key;
            $value2 = $file2Data->$key;

            if (is_object($value1) && is_object($value2)) {
                $diff[] = ['type' => 'nested', 'key' => $key, 'children' => buildDiff($value1, $value2)];
            } elseif ($value1 === $value2) {
                $diff[] = ['type' => 'unchanged', 'key' => $key, 'value' => $value1];
            } else {
                $diff[] = ['type' => 'changed', 'key' => $key, 'oldValue' => $value1, 'newValue' => $value2];
            }
        }
    }
    return $diff;
}
