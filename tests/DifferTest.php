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

    public function testGenDiffMixedFormat(): void
    {
        $file1 = $this->fixturesPath . 'file1.json';
        $file2 = $this->fixturesPath . 'file2.yml';
        
        $expected = trim(file_get_contents($this->fixturesPath . 'expected_flat.txt'));
        $actual = trim(genDiff($file1, $file2));

        $this->assertEquals($expected, $actual);
    }
}
