<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Gendiff\Formatters\Json\formatJson;

class JsonTest extends TestCase
{
    public function testFormatJson()
    {
        $diff = [
            ['type' => 'unchanged', 'key' => 'a', 'value' => 1],
            ['type' => 'added', 'key' => 'b', 'value' => 2],
        ];

        $expectedPath = realpath(__DIR__ . '/../../Fixtures/Formatters/expectedFormatJson.txt');
        $expected = file_get_contents($expectedPath);

        $this->assertEquals($expected, formatJson($diff));
    }
}
