<?php
//
//namespace App;
//
//require __DIR__ . '/vendor/autoload.php';
//
//use Exception;
//use Symfony\Component\Yaml\Yaml;
//
//function debug($data): void
//{
//    echo "<pre>" . print_r($data, true) . "</pre>";
//}
//
//function formatPlain(array $diff, string $parentKey = ''): string
//{
//    $lines = [];
//
//    foreach ($diff as $node) {
//        $key = $node['key'];
//        $fullKey = $parentKey ? "{$parentKey}.{$key}" : $key;
//        $type = $node['type'];
//
//        switch ($type) {
//            case 'nested':
//                $lines[] = formatPlain($node['children'], $fullKey);
//                break;
//            case 'added':
//                $value = stringifyPlainValue($node['value']);
//                $lines[] = "Property '{$fullKey}' was added with value: {$value}";
//                break;
//            case 'removed':
//                $lines[] = "Property '{$fullKey}' was removed";
//                break;
//            case 'changed':
//                $oldValue = stringifyPlainValue($node['oldValue']);
//                $newValue = stringifyPlainValue($node['newValue']);
//                $lines[] = "Property '{$fullKey}' was updated. From {$oldValue} to {$newValue}";
//                break;
//            case 'unchanged':
//                break;
//        }
//    }
//
//    return implode("\n", $lines);
//}
//
//function stringifyPlainValue($value): string
//{
//    if (is_object($value) || is_array($value)) {
//        return '[complex value]';
//    }
//    if (is_bool($value)) {
//        return $value ? 'true' : 'false';
//    }
//    if (is_null($value)) {
//        return 'null';
//    }
//    if (is_string($value)) {
//        return "'{$value}'";
//    }
//    return (string)$value;
//}
//
//function formatStylish(array $diff, int $depth = 0): string
//{
//    $indent = str_repeat('    ', $depth);
//    $result = ["{"];
//
//    foreach ($diff as $node) {
//        $key = $node['key'];
//        $type = $node['type'];
//
//        switch ($type) {
//            case 'nested':
//                $childrenFormatted = formatStylish($node['children'], $depth + 1);
//                $result[] = "{$indent}    {$key}: {$childrenFormatted}";
//                break;
//            case 'unchanged':
//                $value = stringifyValue($node['value'], $depth + 1);
//                $result[] = "{$indent}    {$key}: {$value}";
//                break;
//            case 'added':
//                $value = stringifyValue($node['value'], $depth + 1);
//                $result[] = "{$indent}  + {$key}: {$value}";
//                break;
//            case 'removed':
//                $value = stringifyValue($node['value'], $depth + 1);
//                $result[] = "{$indent}  - {$key}: {$value}";
//                break;
//            case 'changed':
//                $oldValue = stringifyValue($node['oldValue'], $depth + 1);
//                $newValue = stringifyValue($node['newValue'], $depth + 1);
//                $result[] = "{$indent}  - {$key}: {$oldValue}";
//                $result[] = "{$indent}  + {$key}: {$newValue}";
//                break;
//        }
//    }
//    $result[] = "{$indent}}";
//    return implode("\n", $result);
//}
//
//function stringifyValue($value, int $depth = 0): string
//{
//    if (is_bool($value)) {
//        return $value ? 'true' : 'false';
//    }
//    if (is_null($value)) {
//        return 'null';
//    }
//    if (is_object($value) || is_array($value)) {
//        $indent = str_repeat('    ', $depth);
//        $bracketIndent = str_repeat('    ', $depth - 1);
//        $result = ["{"];
//        $items = is_object($value) ? get_object_vars($value) : $value;
//        foreach ($items as $key => $item) {
//            $itemValue = stringifyValue($item, $depth + 1);
//            $result[] = "{$indent}    {$key}: {$itemValue}";
//        }
//        $result[] = "{$bracketIndent}    }";
//        return implode("\n", $result);
//    }
//    return (string)$value;
//}
//
///**
// * @throws Exception
// */
//function genDiff(string $filepath1, string $filepath2, string $format = 'stylish'): string
//{
//    $file1Data = parseFile($filepath1);
//    $file2Data = parseFile($filepath2);
//    $diff = buildDiff($file1Data, $file2Data);
//    return format($diff, $format);
//}
//
//function buildDiff(object $file1Data, object $file2Data): array
//{
//    $keys1 = array_keys(get_object_vars($file1Data));
//    $keys2 = array_keys(get_object_vars($file2Data));
//    $allKeys = array_unique(array_merge($keys1, $keys2));
//    sort($allKeys);
//    $diff = [];
//    foreach ($allKeys as $key) {
//        $hasKey1 = property_exists($file1Data, $key);
//        $hasKey2 = property_exists($file2Data, $key);
//
//
//        if ($hasKey1 && !$hasKey2) {
//            $diff[] = ['type' => 'removed', 'key' => $key, 'value' => $file1Data->$key];
//        } elseif (!$hasKey1 && $hasKey2) {
//            $diff[] = ['type' => 'added', 'key' => $key, 'value' => $file2Data->$key];
//        } elseif ($hasKey1 && $hasKey2) {
//            $value1 = $file1Data->$key;
//            $value2 = $file2Data->$key;
//
//            if (is_object($value1) && is_object($value2)) {
//                $diff[] = ['type' => 'nested', 'key' => $key, 'children' => buildDiff($value1, $value2)];
//            } elseif ($value1 === $value2) {
//                $diff[] = ['type' => 'unchanged', 'key' => $key, 'value' => $value1];
//            } else {
//                $diff[] = ['type' => 'changed', 'key' => $key, 'oldValue' => $value1, 'newValue' => $value2];
//            }
//        }
//    }
//    return $diff;
//}
//
///**
// * @throws Exception
// */
//function format(array $diff, string $formatName): string
//{
//    return match ($formatName) {
//        'stylish' => formatStylish($diff),
//        'plain' => formatPlain($diff),
//        default => throw new Exception("Unknown format: {$formatName}"),
//    };
//}
//
//function parseFile(string $filepath): object
//{
//    $content = file_get_contents($filepath);
//    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
//
//    return match ($extension) {
//        'json' => json_decode($content),
//        'yaml', 'yml' => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
//        default => throw new \Exception("Unsupported file format: {$extension}"),
//    };
//}
//
//
//
//$filePath1 = 'tests\Fixtures\fileNested1.json';
//$filePath2 = 'tests\Fixtures\fileNested2.json';
//$filePathPlain = 'tests\Fixtures\expectedPlain.txt';
//
//
//
//$diff = genDiff($filePath1, $filePath2, 'plain');
//debug($diff);
////file_put_contents($filePathPlain, $diff);
