<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;

use function App\Differ\{buildDiff, genDiff};

class DifferTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenDiffJsonYamlNested()
    {
        $jsonPath1 = realpath(__DIR__ . '/../Fixtures/fileNested1.json');
        $jsonPath2 = realpath(__DIR__ . '/../Fixtures/fileNested2.json');
        $yamlPath1 = realpath(__DIR__ . '/../Fixtures/fileNested1.yaml');
        $yamlPath2 = realpath(__DIR__ . '/../Fixtures/fileNested2.yaml');
        $expectedPathStylish = realpath(__DIR__ . '/../Fixtures/expectedNestedStylish.txt');
        $expectedStylish = file_get_contents($expectedPathStylish);

        $this->assertEquals($expectedStylish, genDiff($jsonPath1, $jsonPath2));
        $this->assertEquals($expectedStylish, genDiff($yamlPath1, $yamlPath2));

        $expectedPathPlain = realpath(__DIR__ . '/../Fixtures/expectedPlain.txt');
        $expectedPlain = file_get_contents($expectedPathPlain);
        $this->assertEquals($expectedPlain, genDiff($jsonPath1, $jsonPath2, 'plain'));
    }

    public function testBuildDiff()
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
}
