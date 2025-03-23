<?php

namespace Diff\Core;

use Docopt\Handler;
use Docopt\LanguageError;
use Exception;

use function Diff\Parsing\getContentJson;

/**
 * @throws LanguageError
 * @throws Exception
 */
function runGendiff(): void
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
  --format <fmt>                Report format [default: stylish]
DOC;

    $handler = new Handler();
    $response = $handler->handle($doc);

    if ($response['--help']) {
        echo $doc;
    } elseif ($response['--version']) {
        echo "gendiff 1.0.0\n";
    } elseif (!empty($response['<firstFile>']) && !empty($response['<secondFile>'])) {
        $firstFile = $response['<firstFile>'];
        $secondFile = $response['<secondFile>'];
        $format = $response['--format'];

        $firstData = getContentJson($firstFile);
        if ($firstData === null) {
            throw new Exception("Failed to read or parse first file.");
        }

        $secondData = getContentJson($secondFile);
        if ($secondData === null) {
            throw new Exception("Failed to read or parse second file.");
        }

        echo "First file content:\n";
        echo json_encode($firstData, JSON_PRETTY_PRINT) . "\n";

        echo "Second file content:\n";
        echo json_encode($secondData, JSON_PRETTY_PRINT) . "\n";
    }
}
