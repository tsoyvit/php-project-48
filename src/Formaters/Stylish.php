<?php

namespace App\Stylish;

use Exception;

function formatStylish(array $diff, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);
    $result = ["{"];

    foreach ($diff as $node) {
        $key = $node['key'];
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $childrenFormatted = formatStylish($node['children'], $depth + 1);
                $result[] = "{$indent}    {$key}: {$childrenFormatted}";
                break;
            case 'unchanged':
                $value = stringifyValue($node['value'], $depth + 1);
                $result[] = "{$indent}    {$key}: {$value}";
                break;
            case 'added':
                $value = stringifyValue($node['value'], $depth + 1);
                $result[] = "{$indent}  + {$key}: {$value}";
                break;
            case 'removed':
                $value = stringifyValue($node['value'], $depth + 1);
                $result[] = "{$indent}  - {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = stringifyValue($node['oldValue'], $depth + 1);
                $newValue = stringifyValue($node['newValue'], $depth + 1);
                $result[] = "{$indent}  - {$key}: {$oldValue}";
                $result[] = "{$indent}  + {$key}: {$newValue}";
                break;
        }
    }
    $result[] = "{$indent}}";
    return implode("\n", $result);
}

function stringifyValue($value, int $depth = 0): string
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
        $result = ["{"];
        $items = is_object($value) ? get_object_vars($value) : $value;
        foreach ($items as $key => $item) {
            $itemValue = stringifyValue($item, $depth + 1);
            $result[] = "{$indent}    {$key}: {$itemValue}";
        }
        $result[] = "{$bracketIndent}    }";
        return implode("\n", $result);
    }
    return (string)$value;
}
