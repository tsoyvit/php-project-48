<?php

namespace App\Differ;

use Exception;

use function App\Parser\formatValue;
use function App\Parser\getContentFile;

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

/**
 * @throws Exception
 */
function genDiff($filePath1, $filePath2): string
{
    $firstData = getContentFile($filePath1);
    $secondData = getContentFile($filePath2);
    $diff = compareFiles($firstData, $secondData);
    $lines = array_map(fn($line) => "  {$line}", $diff);
    return "{\n" . implode("\n", $lines) . "\n}";
}
