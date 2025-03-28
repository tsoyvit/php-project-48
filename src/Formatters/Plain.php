<?php

namespace App\Formatters\Plain;

function formatPlain(array $diff, string $parentKey = ''): string
{
    $lines = [];

    foreach ($diff as $node) {
        $key = $node['key'];
        $fullKey = $parentKey ? "{$parentKey}.{$key}" : $key;
        $type = $node['type'];

        switch ($type) {
            case 'nested':
                $lines[] = formatPlain($node['children'], $fullKey);
                break;
            case 'added':
                $value = stringifyPlainValue($node['value']);
                $lines[] = "Property '{$fullKey}' was added with value: {$value}";
                break;
            case 'removed':
                $lines[] = "Property '{$fullKey}' was removed";
                break;
            case 'changed':
                $oldValue = stringifyPlainValue($node['oldValue']);
                $newValue = stringifyPlainValue($node['newValue']);
                $lines[] = "Property '{$fullKey}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'unchanged':
                break;
        }
    }

    return implode("\n", $lines);
}

function stringifyPlainValue($value): string
{
    if (is_object($value) || is_array($value)) {
        return '[complex value]';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return (string)$value;
}
