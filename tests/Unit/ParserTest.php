<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Differ\Parser\parseFile;

class ParserTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testParseFile()
    {
        $jsonPath1 = realpath(__DIR__ . '/../Fixtures/file1.json');
        $yamlPath1 = realpath(__DIR__ . '/../Fixtures/file1.yaml');
        $expectedJson = json_decode(file_get_contents($jsonPath1));
        $expectedYaml = Yaml::parse(file_get_contents($yamlPath1), Yaml::PARSE_OBJECT_FOR_MAP);
        $this->assertEquals($expectedJson, parseFile($jsonPath1));
        $this->assertEquals($expectedYaml, parseFile($yamlPath1));
    }
}
