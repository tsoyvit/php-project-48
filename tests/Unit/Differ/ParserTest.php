<?php

namespace Tests\Unit\Differ;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function App\Differ\{getContentJson, compareFiles, formatValue, genDiff};

class ParserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testReturnsSortedArrayForValidJsonFile()
    {
        $filePath = tempnam(sys_get_temp_dir(), 'json');
        file_put_contents($filePath, '{"b":2,"a":1}');
        $result = getContentJson($filePath);
        $this->assertSame(['a' => 1, 'b' => 2], $result);
        unlink($filePath);
    }

    public function testThrowsExceptionIfFileDoesNotExist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("does not exist");
        getContentJson('/path/to/nonexistent/file.json');
    }
    public function testThrowsExceptionIfJsonIsInvalid()
    {
        $filePath = tempnam(sys_get_temp_dir(), 'json');
        file_put_contents($filePath, '{invalid json');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid JSON");

        getContentJson($filePath);
        unlink($filePath);
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

    public function testCompareFilesBasicComparison()
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
    public function testGenDiffBasicCase()
    {
        $file1 = tempnam(sys_get_temp_dir(), 'test1');
        $file2 = tempnam(sys_get_temp_dir(), 'test2');

        file_put_contents($file1, '{"name": "John", "age": 30}');
        file_put_contents($file2, '{"name": "John", "age": 31, "city": "NY"}');


        $expected = "{\n" .
            "  - age: 30\n" .
            "  + age: 31\n" .
            "    name: John\n" .
            "  + city: NY\n" .
            "}";

        $result = genDiff($file1, $file2);

        $normalizedExpected = str_replace("\r\n", "\n", $expected);
        $normalizedResult = str_replace("\r\n", "\n", $result);

        $this->assertEquals($normalizedExpected, $normalizedResult);

        unlink($file1);
        unlink($file2);
    }
}
