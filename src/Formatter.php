<?php

namespace App\Formatters;

use Exception;

use function App\Formatters\Stylish\formatStylish;
use function App\Formatters\Plain\formatPlain;

/**
 * @throws Exception
 */
function format(array $diff, string $formatName): string
{
    return match ($formatName) {
        'stylish' => formatStylish($diff),
        'plain' => formatPlain($diff),
        default => throw new Exception("Unknown format: {$formatName}"),
    };
}
