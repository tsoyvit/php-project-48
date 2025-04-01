<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffJsonYamlNested()
    {
        // Фикстуры
        $jsonPath1 = $this->getFixtureFullPath('fileNested1.json');
        $jsonPath2 = $this->getFixtureFullPath('fileNested2.json');
        $yamlPath1 = $this->getFixtureFullPath('fileNested1.yaml');
        $yamlPath2 = $this->getFixtureFullPath('fileNested2.yaml');
        // формат вывода stylish
        $expectedStylish = $this->getContentFixture('expectedNestedStylish.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2));
        // формат вывода plain
        $expectedStylish = $this->getContentFixture('expectedNestedPlain.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'plain'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'plain'));
        // формат вывода json
        $expectedStylish = $this->getContentFixture('expectedNestedJson.txt');
        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2, 'json'));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2, 'json'));
    }

    public function getFixtureFullPath($fixtureName): false|string
    {
        $parts = [__DIR__, 'Fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function getContentFixture($fixtureName): false|string
    {
        return file_get_contents($this->getFixtureFullPath($fixtureName));
    }
}
