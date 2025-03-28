<?php

namespace App\Cli;

use Docopt\Handler;
use Docopt\LanguageError;
use Exception;

use function App\Differ\genDiff;

/**
 * @throws LanguageError
 * @throws Exception
 */
function runCli(): void
{
    $doc = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format (stylish, plain) [default: stylish]
DOC;

    $handler = new Handler();

    $response = $handler->handle($doc);
    if ($response['--help']) {
        echo $doc;
    } elseif ($response['--version']) {
        echo "gendiff 1.0.0\n";
    } elseif (!empty($response['<firstFile>']) && !empty($response['<secondFile>'])) {
        $format = $response['--format'];
        $diff = genDiff($response['<firstFile>'], $response['<secondFile>'], $format);
        echo $diff . "\n";
    }
}
