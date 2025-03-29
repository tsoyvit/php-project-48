<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use function Differ\Formatters\Plain\stringifyPlainValue;
use function Differ\Formatters\Stylish\stringifyValue;

class DifferTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenDiffJsonYamlNested()
    {
        // Фикстуры
        $jsonPath1 = realpath(__DIR__ . '/../Fixtures/fileNested1.json');
        $jsonPath2 = realpath(__DIR__ . '/../Fixtures/fileNested2.json');
        $yamlPath1 = realpath(__DIR__ . '/../Fixtures/fileNested1.yaml');
        $yamlPath2 = realpath(__DIR__ . '/../Fixtures/fileNested2.yaml');
        // формат вывода stylish
        $expectedPathStylish = realpath(__DIR__ . '/../Fixtures/Formatters/expectedNestedStylish.txt');
        $expectedStylish = file_get_contents($expectedPathStylish);
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2));
        // формат вывода plain
        $expectedPathStylish = realpath(__DIR__ . '/../Fixtures/Formatters/expectedNestedPlain.txt');
        $expectedStylish = file_get_contents($expectedPathStylish);
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'plain'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'plain'));
        // формат вывода json
        $expectedPathStylish = realpath(__DIR__ . '/../Fixtures/Formatters/expectedNestedJson.txt');
        $expectedStylish = file_get_contents($expectedPathStylish);
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'json'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'json'));
    }

    public function testStringifyValuePlain()
    {
        $this->assertEquals('true', stringifyPlainValue(true));
        $this->assertEquals('false', stringifyPlainValue(false));
        $this->assertEquals('null', stringifyPlainValue(null));
        $this->assertEquals('123', stringifyPlainValue(123));
    }

    public function testStringifyValue()
    {
        $this->assertEquals('true', stringifyValue(true));
        $this->assertEquals('false', stringifyValue(false));
        $this->assertEquals('null', stringifyValue(null));
        $this->assertEquals('123', stringifyValue(123));
        $this->assertEquals('hello', stringifyValue('hello'));
    }
}
