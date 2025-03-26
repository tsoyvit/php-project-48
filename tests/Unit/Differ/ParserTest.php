<?php

namespace Tests\Unit\Differ;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function App\Differ\{compareFiles, genDiff};
use function App\Parser\{getContentFile, formatValue};

class ParserTest extends TestCase
{
    public function testThrowsExceptionIfFileDoesNotExist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("does not exist");
        getContentFile('/path/to/nonexistent/file.json');
    }


    #[DataProvider('formatValueProvider')]
    public function testFormatValue($input, $expected): void
    {
        $this->assertEquals($expected, formatValue($input));
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

    public function testCompareFiles()
    {
        $firstData = [
            'name' => 'John',
            'age' => 30,
            'city' => 'New York'
        ];

        $secondData = [
            'name' => 'John',
            'age' => 31,
            'country' => 'USA'
        ];

        $expected = [
            '  name: John',
            '- age: 30',
            '+ age: 31',
            '- city: New York',
            '+ country: USA'
        ];
        $result = compareFiles($firstData, $secondData);

        $this->assertEquals($expected, $result);
    }

    public function testCompareFilesWithEmptyArrays()
    {
        $this->assertEquals([], compareFiles([], []));
        $secondData = ['key' => 'value'];
        $expected = ['+ key: value'];
        $this->assertEquals($expected, compareFiles([], $secondData));

        $firstData = ['key' => 'value'];
        $expected = ['- key: value'];
        $this->assertEquals($expected, compareFiles($firstData, []));
    }

    /**
     * @throws Exception
     */
    public function testGenDiffJson()
    {
        $filePath1 = realpath(__DIR__ . '/../../Fixtures/file1.json');
        $filePath2 = realpath(__DIR__ . '/../../Fixtures/file2.json');
        $expected = "{\n" .
            "  - follow: false\n" .
            "    host: hexlet.io\n" .
            "  - proxy: 123.234.53.22\n" .
            "  - timeout: 50\n" .
            "  + timeout: 20\n" .
            "  + verbose: true\n" .
            "}";
        $this->assertEquals($expected, genDiff($filePath1, $filePath2));
    }

    /**
     * @throws Exception
     */
    public function testGenDiffYaml()
    {
        $filePath1 = realpath(__DIR__ . '/../../Fixtures/file1.yaml');
        $filePath2 = realpath(__DIR__ . '/../../Fixtures/file2.yaml');
        $expected = "{\n" .
            "  - follow: false\n" .
            "    host: hexlet.io\n" .
            "  - proxy: 123.234.53.22\n" .
            "  - timeout: 50\n" .
            "  + timeout: 20\n" .
            "  + verbose: true\n" .
            "}";
        $this->assertEquals($expected, genDiff($filePath1, $filePath2));
    }

    /**
     * @throws Exception
     */
    public function testGetContentFileYaml()
    {
        $filePath = realpath(__DIR__ . '/../../Fixtures/file1.yaml');
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => false
        ];
        $this->assertEquals($expected, getContentFile($filePath));
    }
}
