<?php

namespace Differ\Formatter;

use Exception;

use function Differ\Formatters\Json\formatJson;
use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;

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
