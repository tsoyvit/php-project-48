<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function Gendiff\Formatters\Plain\{formatPlain, stringifyPlainValue};

class PlainTest extends TestCase
{
    public function testStringifyValuePlain()
    {
        $this->assertEquals('true', stringifyPlainValue(true));
        $this->assertEquals('false', stringifyPlainValue(false));
        $this->assertEquals('null', stringifyPlainValue(null));
        $this->assertEquals('123', stringifyPlainValue(123));
    }

    public function testFormatPlain()
    {
        $filePath = realpath(__DIR__ . '/../../Fixtures/Formatters/fixture.json');
        $fixture = json_decode(file_get_contents($filePath), true);

        $expectedPath = realpath(__DIR__ . '/../../Fixtures/Formatters/expectedNestedPlain.txt');
        $expected = file_get_contents($expectedPath);

        $this->assertEquals($expected, formatPlain($fixture));
    }
}
