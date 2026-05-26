<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->fixturesPath = __DIR__ . '/fixtures/';
    }

    // 1. Тесты для формата STYLISH (по умолчанию)
    public function testGenDiffStylish(): void
    {
        $file1 = "{$this->fixturesPath}file1.json";
        $file2 = "{$this->fixturesPath}file2.json";
        $expected = trim(file_get_contents("{$this->fixturesPath}expected_nested.txt"));
        
        $this->assertEquals($expected, trim(genDiff($file1, $file2, 'stylish')));
        $this->assertEquals($expected, trim(genDiff($file1, $file2))); // Проверка дефолта
    }

    // 2. Тесты для формата PLAIN
    public function testGenDiffPlain(): void
    {
        $file1 = "{$this->fixturesPath}file1.json";
        $file2 = "{$this->fixturesPath}file2.yaml"; // Смешанный тест JSON + YAML
        $expected = trim(file_get_contents("{$this->fixturesPath}expected_plain.txt"));

        $this->assertEquals($expected, trim(genDiff($file1, $file2, 'plain')));
    }

    // 3. Тесты для формата JSON
    public function testGenDiffJson(): void
    {
        $file1 = "{$this->fixturesPath}file1.json";
        $file2 = "{$this->fixturesPath}file2.json";
        
        $actual = genDiff($file1, $file2, 'json');

        
        $this->assertJson($actual);
       
        $this->assertStringContainsString('"type": "nested"', $actual);
        $this->assertStringContainsString('"type": "changed"', $actual);
    }
}