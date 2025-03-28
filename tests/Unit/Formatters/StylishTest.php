<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function App\Formatters\Stylish\{formatStylish, stringifyValue};

class StylishTest extends TestCase
{
    public function testStringifyValueSimpleTypes()
    {
        $this->assertEquals('true', stringifyValue(true));
        $this->assertEquals('false', stringifyValue(false));
        $this->assertEquals('null', stringifyValue(null));
        $this->assertEquals('123', stringifyValue(123));
        $this->assertEquals('hello', stringifyValue('hello'));
    }

    public function testFormatStylishUnchangedAndAdded()
    {
        $diff = [
            ['type' => 'unchanged', 'key' => 'a', 'value' => 1],
            ['type' => 'added', 'key' => 'b', 'value' => 2],
        ];
        $expectedString = "{\n    a: 1\n  + b: 2\n}";
        $this->assertEquals($expectedString, formatStylish($diff));
    }

    public function testArrayWithDepth()
    {
        $input = ['key' => 'value'];
        $expected = "{\n        key: value\n    }";
        $this->assertEquals($expected, stringifyValue($input, 1));
    }
}
