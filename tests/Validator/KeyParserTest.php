<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator;

use Kosv\DonationalertsClient\Validator\KeyParser;
use PHPUnit\Framework\TestCase;

final class KeyParserTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testParseToDirectionalList(string $key, array $expectedList): void
    {
        $parser = new KeyParser($key);
        $this->assertEquals($expectedList, $parser->parseToDirectionalList());
    }

    public function validateDataProvider(): array
    {
        return [
            ['', []],
            ['key1', ['key1']],
            ['key1.key2.key3', ['key1', 'key2', 'key3']],
            ['0.1.2', [0, 1, 2]],
            ['key1.2.key3', ['key1', 2, 'key3']],
            ['key1.*', ['key1', '*']],
            ['key1.[*]', ['key1', '[*]']],
            ['..', ['', '', '']],
            ['key1\\.key2.key3', ['key1.key2', 'key3']],
            ['key1\\.key2\\.key3', ['key1.key2.key3']],
            ['\\.\\.', ['..']],
        ];
    }
}
