<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Gendiff\Formatters\Stylish\{formatStylish, stringifyValue};

class StylishTest extends TestCase
{
    public function testStringifyValue()
    {
        $this->assertEquals('true', stringifyValue(true));
        $this->assertEquals('false', stringifyValue(false));
        $this->assertEquals('null', stringifyValue(null));
        $this->assertEquals('123', stringifyValue(123));
        $this->assertEquals('hello', stringifyValue('hello'));
    }

    public function testFormatStylish()
    {
        $filePath = realpath(__DIR__ . '/../../Fixtures/Formatters/fixture.json');
        $fixture = json_decode(file_get_contents($filePath), true);

        $expectedPath = realpath(__DIR__ . '/../../Fixtures/Formatters/expectedNestedStylish.txt');
        $expected = file_get_contents($expectedPath);

        $this->assertEquals($expected, formatStylish($fixture));
    }
}
