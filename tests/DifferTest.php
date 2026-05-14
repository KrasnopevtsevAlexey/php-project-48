<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Hexlet\Code\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->fixturesPath = __DIR__ . '/fixtures/';
    }

    public function testGenDiffNestedJson(): void
    {
        $file1 = $this->fixturesPath . 'file1.json';
        $file2 = $this->fixturesPath . 'file2.json';
        
        $expected = trim(file_get_contents($this->fixturesPath . 'expected_nested.txt'));
        $actual = trim(genDiff($file1, $file2));

        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffNestedYaml(): void
    {
        $file1 = $this->fixturesPath . 'file1.yaml';
        $file2 = $this->fixturesPath . 'file2.yaml';
        
        $expected = trim(file_get_contents($this->fixturesPath . 'expected_nested.txt'));
        $actual = trim(genDiff($file1, $file2));

        $this->assertEquals($expected, $actual);
    }
}