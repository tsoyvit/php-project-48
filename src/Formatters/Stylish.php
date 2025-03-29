<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $diff, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);

    $resultBody = array_map(function ($node) use ($depth) {
        $indentLocal = str_repeat('    ', $depth);
        $key = $node['key'];
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $childrenFormatted = formatStylish($node['children'], $depth + 1);
                return ["{$indentLocal}    {$key}: {$childrenFormatted}"];

            case 'unchanged':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$indentLocal}    {$key}: {$value}"];

            case 'added':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$indentLocal}  + {$key}: {$value}"];

            case 'removed':
                $value = stringifyValue($node['value'], $depth + 1);
                return ["{$indentLocal}  - {$key}: {$value}"];

            case 'changed':
                $oldValue = stringifyValue($node['oldValue'], $depth + 1);
                $newValue = stringifyValue($node['newValue'], $depth + 1);
                return [
                    "{$indentLocal}  - {$key}: {$oldValue}",
                    "{$indentLocal}  + {$key}: {$newValue}"
                ];
        }
        return [];
    }, $diff);

    $flattenedBody = array_merge([], ...$resultBody);
    $result = array_merge(["{"], $flattenedBody, ["{$indent}}"]);
    return implode("\n", $result);
}

function stringifyValue(mixed $value, int $depth = 0): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_object($value) || is_array($value)) {
        $indent = str_repeat('    ', $depth);
        $bracketIndent = str_repeat('    ', $depth - 1);
        $items = is_object($value) ? get_object_vars($value) : $value;
        $lines = array_map(function ($key) use ($items, $depth, $indent) {
            $itemValue = stringifyValue($items[$key], $depth + 1);
            return "{$indent}    {$key}: {$itemValue}";
        }, array_keys($items));
        $result = array_merge(["{"], $lines, ["{$bracketIndent}    }"]);
        return implode("\n", $result);
    }
    return (string)$value;
}
