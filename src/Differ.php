<?php

namespace Differ\Differ;

use Exception;

use function Functional\sort;
use function Functional\reduce_left;
use function Differ\Parser\parseFile;
use function Differ\Formatter\format;

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

    $sortedKeys = sort($allKeys, fn($a, $b) => strcmp($a, $b));

    return reduce_left($sortedKeys, function ($key, $index, $collection, $reduction) use ($file1Data, $file2Data) {
        $hasKey1 = property_exists($file1Data, $key);
        $hasKey2 = property_exists($file2Data, $key);

        if ($hasKey1 && !$hasKey2) {
            return array_merge($reduction, [[
                'type' => 'removed',
                'key' => $key,
                'value' => $file1Data->$key
            ]]);
        }

        if (!$hasKey1 && $hasKey2) {
            return array_merge($reduction, [[
                'type' => 'added',
                'key' => $key,
                'value' => $file2Data->$key
            ]]);
        }

        if ($hasKey1 && $hasKey2) {
            $value1 = $file1Data->$key;
            $value2 = $file2Data->$key;

            if (is_object($value1) && is_object($value2)) {
                return array_merge($reduction, [[
                    'type' => 'nested',
                    'key' => $key,
                    'children' => buildDiff($value1, $value2)
                ]]);
            }

            if ($value1 === $value2) {
                return array_merge($reduction, [[
                    'type' => 'unchanged',
                    'key' => $key,
                    'value' => $value1
                ]]);
            }

            return array_merge($reduction, [[
                'type' => 'changed',
                'key' => $key,
                'oldValue' => $value1,
                'newValue' => $value2
            ]]);
        }

        return $reduction;
    }, []);
}
