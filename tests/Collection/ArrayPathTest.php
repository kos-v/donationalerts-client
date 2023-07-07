<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Collection;

use Kosv\DonationalertsClient\Collection\ArrayPath;
use PHPUnit\Framework\TestCase;

final class ArrayPathTest extends TestCase
{
    /**
     * @dataProvider getFullPathDataProvider
     */
    public function testGetFullPath($fullPath, $expectedFullPath): void
    {
        $pathObj = new ArrayPath($fullPath);
        $this->assertEquals($expectedFullPath, $pathObj->getFullPath());
    }

    public function getFullPathDataProvider(): array
    {
        return [
            [null, null],
            [0, 0],
            [[], []],
            [(object)[], (object)[]],
            ['', ''],
            ['key1', 'key1'],
            ['0', '0'],
            ['key1.key2.key3', 'key1.key2.key3'],
        ];
    }

    /**
     * @dataProvider toPartsDataProvider
     */
    public function testToParts($fullPath, $expectedParts): void
    {
        $pathObj = new ArrayPath($fullPath);
        $this->assertEquals($expectedParts, $pathObj->toParts());
    }

    public function toPartsDataProvider(): array
    {
        return [
            [null, [null]],
            [0, [0]],
            [[], [[]]],
            [(object)[], [(object)[]]],
            ['', ['']],
            ['key1', ['key1']],
            ['key1.key2.key3', ['key1', 'key2', 'key3']],
            ['0.1.2', [0, 1, 2]],
            ['key1.2.key3', ['key1', 2, 'key3']],
            ['key1.*', ['key1', '*']],
            ['..', ['', '', '']],
            ['key1\\.key2.key3', ['key1.key2', 'key3']],
            ['key1\\.key2\\.key3', ['key1.key2.key3']],
            ['\\.\\.', ['..']],
        ];
    }
}
