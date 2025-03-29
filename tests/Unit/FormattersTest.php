<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;

use function Gendiff\Formatter\format;
use function Gendiff\Formatters\Plain\formatPlain;
use function Gendiff\Formatters\Stylish\formatStylish;

class FormattersTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFormat()
    {

        $diff = [
            ['type' => 'unchanged', 'key' => 'a', 'value' => 1],
            ['type' => 'added', 'key' => 'b', 'value' => 2],
        ];
        $expectedPlain = formatPlain($diff);
        $actual = format($diff, 'plain');
        $this->assertSame($expectedPlain, $actual);

        $expectedStylish = formatStylish($diff);
        $actual = format($diff, 'stylish');
        $this->assertSame($expectedStylish, $actual);
    }
}
