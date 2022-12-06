<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use PHPUnit\Framework\TestCase;

final class IsKeyableArrayRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck($value, string $expectedMsg): void
    {
        $rule = new IsKeyableArrayRule('test_key');
        $this->assertEquals($rule->check($value), $expectedMsg);
    }

    public function checkDataProvider(): array
    {
        return [
            [[], ''],
            [['key1' => 1], ''],
            [['key1' => 1, 'key2' => 2], ''],
            [null, 'The value must be keyable array type'],
            [1, 'The value must be keyable array type'],
            [[[]], 'The value must be keyable array type'],
            [[1, 2, 3], 'The value must be keyable array type'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new IsKeyableArrayRule('test_key', false, 'Error');
        $this->assertEquals($rule->check(null), 'Error');
    }
}
