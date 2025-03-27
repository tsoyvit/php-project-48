<?php

namespace Tests\Unit\Differ;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function App\Differ\{buildDiff, genDiff, format};
use function App\Parser\parseFile;
use function App\Stylish\{formatStylish, stringifyValue};

class ParserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenDiffJsonYaml()
    {
        $jsonPath1 = realpath(__DIR__ . '/../../Fixtures/file1.json');
        $jsonPath2 = realpath(__DIR__ . '/../../Fixtures/file2.json');
        $yamlPath1 = realpath(__DIR__ . '/../../Fixtures/file1.yaml');
        $yamlPath2 = realpath(__DIR__ . '/../../Fixtures/file2.yaml');
        $expectedPath = realpath(__DIR__ . '/../../Fixtures/expectedDiff.txt');
        $expected = file_get_contents($expectedPath);

        $this->assertEquals($expected, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expected, genDiff($yamlPath1, $yamlPath2));
    }

    /**
     * @throws Exception
     */
    public function testGenDiffJsonYamlNested()
    {
        $jsonPath1 = realpath(__DIR__ . '/../../Fixtures/fileNested1.json');
        $jsonPath2 = realpath(__DIR__ . '/../../Fixtures/fileNested2.json');
        $yamlPath1 = realpath(__DIR__ . '/../../Fixtures/fileNested1.yaml');
        $yamlPath2 = realpath(__DIR__ . '/../../Fixtures/fileNested2.yaml');
        $expectedPath = realpath(__DIR__ . '/../../Fixtures/expectedDiffNested.txt');
        $expected = file_get_contents($expectedPath);

        $this->assertEquals($expected, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expected, genDiff($yamlPath1, $yamlPath2));
    }

    public function testBuildDiffAddedProperty()
    {
        $file1Data = (object)['a' => 1, 'b' => 2];
        $file2Data = (object)['a' => 1, 'b' => 2, 'c' => 3];

        $expectedDiff = [
            ['type' => 'unchanged', 'key' => 'a', 'value' => 1],
            ['type' => 'unchanged', 'key' => 'b', 'value' => 2],
            ['type' => 'added', 'key' => 'c', 'value' => 3],
        ];

        $this->assertEquals($expectedDiff, buildDiff($file1Data, $file2Data));
    }

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

    /**
     * @throws Exception
     */
    public function testParseFile()
    {
        $jsonPath1 = realpath(__DIR__ . '/../../Fixtures/file1.json');
        $yamlPath1 = realpath(__DIR__ . '/../../Fixtures/file1.yaml');
        $expectedJson = json_decode(file_get_contents($jsonPath1));
        $expectedYaml = Yaml::parse(file_get_contents($yamlPath1), Yaml::PARSE_OBJECT_FOR_MAP);
        $this->assertEquals($expectedJson, parseFile($jsonPath1));
        $this->assertEquals($expectedYaml, parseFile($yamlPath1));
    }

    public function testArrayWithDepth()
    {
        $input = ['key' => 'value'];
        $expected = "{\n        key: value\n    }";
        $this->assertEquals($expected, stringifyValue($input, 1));
    }
}
