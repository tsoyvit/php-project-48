<?php

namespace Tests\Unit\Differ;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function App\Differ\formatValue;

class ParserTest extends TestCase
{
    #[DataProvider('formatValueProvider')]
    public function testFormatValue($input, $expected): void
    {
        $expected = "false";
        $this->assertEquals($expected, formatValue(false));
    }

    public static function formatValueProvider(): array
    {
        return [
            'boolean true' => [true, 'true'],
            'boolean false' => [false, 'false'],
            'string' => ['text', 'text'],
            'integer' => [123, '123'],
            'float' => [12.3, '12.3'],
            'null' => [null, '']
        ];
    }
}
