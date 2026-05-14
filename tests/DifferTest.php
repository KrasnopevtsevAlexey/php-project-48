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

    public function testGenDiffFlatJson(): void
    {
        $file1 = $this->fixturesPath . 'file1.json';
        $file2 = $this->fixturesPath . 'file2.json';
        $expected = file_get_contents($this->fixturesPath . 'expected_flat.txt');

        $this->assertEquals($expected, genDiff($file1, $file2));
    }
}
