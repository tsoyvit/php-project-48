<?php

namespace App\Formatter;

use Exception;

use function App\Formatters\Json\formatJson;
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
        'json' => formatJson($diff),
        default => throw new Exception("Unknown format: {$formatName}"),
    };
}
