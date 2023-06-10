<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator;

use Kosv\DonationalertsClient\Validator\Key;
use PHPUnit\Framework\TestCase;

final class KeyTest extends TestCase
{
    /**
     * @dataProvider __toStringDataProvider
     */
    public function test__toString(string $key, string $expectedKey): void
    {
        $keyObj = new Key($key);
        $this->assertEquals($expectedKey, (string)$keyObj);
    }

    public function __toStringDataProvider(): array
    {
        return [
            ['', ''],
            ['key1', 'key1'],
            ['0', '0'],
            ['key1.key2.key3', 'key1.key2.key3'],
        ];
    }

    /**
     * @dataProvider getLastPartDataProvider
     */
    public function testGetLastPart(string $key, $expectedPart): void
    {
        $keyObj = new Key($key);
        $this->assertEquals($expectedPart, $keyObj->getLastPart());
    }

    public function getLastPartDataProvider(): array
    {
        return [
            ['', ''],
            ['key1', 'key1'],
            ['0', 0],
            ['key1.key2.key3', 'key3'],
            ['key1.key2.3', 3],
        ];
    }

    /**
     * @dataProvider toPartsDataProvider
     */
    public function testToParts(string $key, array $expectedList): void
    {
        $keyObj = new Key($key);
        $this->assertEquals($expectedList, $keyObj->toParts());
    }

    public function toPartsDataProvider(): array
    {
        return [
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
