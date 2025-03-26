<?php

namespace App\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @throws Exception
 */
function getContentFile($filePath)
{
    $absolutePath = realpath($filePath);
    if ($absolutePath === false) {
        throw new Exception("File '{$filePath}' does not exist");
    }
    $content = file_get_contents($absolutePath);
    if (false === $content) {
        throw new Exception("Unable to read file '{$filePath}'.");
    }
    $dataParse = [];
    if (str_ends_with($filePath, ".json")) {
        $dataParse = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in file '{$filePath}'.");
        }
    }
    if (str_ends_with($filePath, ".yml") || str_ends_with($filePath, ".yaml")) {
        $dataParse = Yaml::parse($content);
    }
    ksort($dataParse);
    return $dataParse;
}

function formatValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return (string) $value;
}

//function compareFiles(array $firstData, array $secondData): array
//{
//    $diff = [];
//    foreach ($firstData as $key => $value) {
//        $firstValue = formatValue($value);
//        if (array_key_exists($key, $secondData)) {
//            $secondValue = $secondData[$key];
//            $formattedSecondValue = formatValue($secondValue);
//            if ($value == $secondValue) {
//                $diff[] = "  {$key}: {$firstValue}";
//            } else {
//                $diff[] = "- {$key}: {$firstValue}";
//                $diff[] = "+ {$key}: {$formattedSecondValue}";
//            }
//        } else {
//            $diff[] = "- {$key}: {$firstValue}";
//        }
//    }
//    foreach ($secondData as $subKey => $subValue) {
//        if (!array_key_exists($subKey, $firstData)) {
//            $secondSubValue = formatValue($subValue);
//            $diff[] = "+ {$subKey}: {$secondSubValue}";
//        }
//    }
//    return $diff;
//}

///**
// * @throws Exception
// */
//function genDiff($filePath1, $filePath2): string
//{
//    $firstData = getContentFile($filePath1);
//    $secondData = getContentFile($filePath2);
//    $diff = compareFiles($firstData, $secondData);
//    $lines = array_map(fn($line) => "  {$line}", $diff);
//    return "{\n" . implode("\n", $lines) . "\n}";
//}
