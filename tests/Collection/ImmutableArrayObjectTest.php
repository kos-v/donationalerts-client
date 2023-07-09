<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Collection;

use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use PHPUnit\Framework\TestCase;

final class ImmutableArrayObjectTest extends TestCase
{
    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(string $msg, array $initArray, array $expectedArray): void
    {
        $arrObj = new ImmutableArrayObject($initArray);
        $this->assertEquals($expectedArray, $arrObj->toArray(), $msg);
    }

    public function toArrayDataProvider(): array
    {
        return [
            [
                'Empty init array',
                [],
                []
            ],
            [
                'Init array as list',
                [1, 2, 3],
                [1, 2, 3]
            ],
            [
                'Init array as dict',
                ['a' => 1, 'b' => 2, 'c' => 3],
                ['a' => 1, 'b' => 2, 'c' => 3]
            ],
            [
                'Mixed init array',
                [
                    'a' => null,
                    'b' => 1,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                ],
                [
                    'a' => null,
                    'b' => 1,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                ],
            ],
        ];
    }

    /**
     * @dataProvider setDataProvider
     */
    public function testSet(string $msg, array $initArray, array $setValue, array $expectedArray): void
    {
        $arrObj = new ImmutableArrayObject($initArray);
        $this->assertEquals($expectedArray, $arrObj->set($setValue)->toArray(), $msg);
    }

    public function setDataProvider(): array
    {
        return [
            [
                'All empty',
                [],
                [],
                []
            ],
            [
                'Empty init & case with nonempty set',
                [],
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f.g.h' => 5,
                ],
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f' => ['g' => ['h' => 5]],
                ],
            ],
            [
                'Empty init & case with self overridden set',
                [],
                [
                    1,
                    [2, ['a' => 3, 'b' => 'str1']],
                    'c' => ['d' => ['e' => 4]],
                    'f' => ['g' => ['h' => 5]],
                    'i' => ['j' => 6],
                    0 => 100,
                    '1.0' => 200,
                    '1.1.a' => 300,
                    'c.d.e' => 400,
                    'f.g' => 'str2',
                    'i.j.k' => 600,
                ],
                [
                    100,
                    [200, ['a' => 300, 'b' => 'str1']],
                    'c' => ['d' => ['e' => 400]],
                    'f' => ['g' => 'str2'],
                    'i' => ['j' => ['k' => 600]],
                ],
            ],
            [
                'Nonempty init & case with empty set',
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f' => ['g' => ['h' => 5]],
                    'i' => [6, 'j' => 7]
                ],
                [],
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f' => ['g' => ['h' => 5]],
                    'i' => [6, 'j' => 7]
                ],
            ],
            [
                'Nonempty init & case with nonempty set',
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f' => ['g' => ['h' => 5]],
                    'i' => [6, 'j' => 7],
                    'k' => ['l' => ['m' => 5], 'o' => 8],
                ],
                [
                    100,
                    'a' => 'str1',
                    'b' => 200,
                    'c' => 300,
                    'd.0' => 400,
                    'd.1' => null,
                    'd.2' => 'str2',
                    'e' => null,
                    500,
                    'f.g.h' => 600,
                    'i.0' => 700,
                    'i.1' => 800,
                    'i.j' => 900,
                    'k.l' => null,
                    'k.o.p' => 1000,
                    1100,
                    'q' => 1200,
                    'r.s' => 1300,
                    'r.t' => 1400,
                ],
                [
                    100,
                    'a' => 'str1',
                    'b' => 200,
                    'c' => 300,
                    'd' => [400, null, 'str2'],
                    'e' => null,
                    500,
                    'f' => ['g' => ['h' => 600]],
                    'i' => [700, 800, 'j' => 900],
                    'k' => ['l' => null, 'o' => ['p' => 1000]],
                    1100,
                    'q' => 1200,
                    'r' => ['s' => 1300, 't' => 1400],
                ],
            ],
            [
                'Nonempty init & case with self overridden set',
                [
                    [1, ['a' => 2, 'b' => 'str1']],
                    'c' => ['d' => ['e' => 3]],
                    'f' => ['g' => 4],
                ],
                [
                    '0' => 10,
                    'c' => 20,
                    'f' => ['g' => 30],
                    '0.a' => 40,
                    'c.d' => 50,
                    'f.h.i' => 60,
                ],
                [
                    ['a' => 40],
                    'c' => ['d' => 50],
                    'f' => ['g' => 30, 'h' => ['i' => 60]],
                ]
            ],
        ];
    }

    /**
     * @dataProvider unsetDataProvider
     */
    public function testUnset(string $msg, array $initArray, array $unsetKeys, array $expectedArray): void
    {
        $arrObj = new ImmutableArrayObject($initArray);
        $this->assertEquals($expectedArray, $arrObj->unset(...$unsetKeys)->toArray(), $msg);
    }

    public function unsetDataProvider(): array
    {
        return [
            [
                'All empty',
                [],
                [],
                [],
            ],
            [
                'Empty init & nonempty unset',
                [],
                [
                    0,
                    'a',
                    '1.b.c',
                    'd.e.f',
                ],
                [],
            ],
            [
                'Empty init & nonempty unset with self overridden',
                [],
                [
                    '1.a.b',
                    'c.d.e',
                    '1.a',
                    'c',
                ],
                [],
            ],
            [
                'Nonempty init & empty unset',
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                ],
                [],
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                ],
            ],
            [
                'Nonempty init & nonempty unset',
                [
                    1,
                    'a' => null,
                    'b' => 2,
                    'c' => 3.14,
                    'd' => [1, 2, 3],
                    'e' => 'str',
                    4,
                    'f' => ['g' => ['h' => 5]],
                    'i' => [6, 'j' => 7],
                    'k' => ['l' => ['m' => 5], 'o' => 8],
                ],
                [
                    0,
                    'a',
                    'c',
                    'd.1',
                    'e',
                    'f.g.h',
                    'f.g',
                    'f',
                    'i.0',
                    'i.j',
                    'k'
                ],
                [
                    'b' => 2,
                    'd' => [0 => 1, 2 => 3],
                    'i' => [],
                    1 => 4,
                ],
            ],
            [
                'Nonempty init & nonempty unset with self overridden',
                [
                    [1, 2, 3],
                    'a' => [4, 5, 6],
                    'b' => ['c' => ['d' => 7]],
                ],
                [
                    0,
                    '0.1',
                    'a',
                    'a.0',
                    'b.c',
                    'b.c.d',
                ],
                [
                    'b' => [],
                ]
            ],
        ];
    }
}
