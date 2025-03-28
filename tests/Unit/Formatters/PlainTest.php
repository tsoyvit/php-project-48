<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use function App\Formatters\Plain\{formatPlain, stringifyPlainValue};

class PlainTest extends TestCase
{
    public function testStringifyValuePlain()
    {
        $this->assertEquals('true', stringifyPlainValue(true));
        $this->assertEquals('false', stringifyPlainValue(false));
        $this->assertEquals('null', stringifyPlainValue(null));
        $this->assertEquals('123', stringifyPlainValue(123));
    }
}
