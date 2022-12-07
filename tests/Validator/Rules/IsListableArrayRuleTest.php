<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\IsListableArrayRule;
use PHPUnit\Framework\TestCase;

final class IsListableArrayRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck($value, string $expectedMsg): void
    {
        $rule = new IsListableArrayRule('test_key');
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [[], ''],
            [[1], ''],
            [[1, '', ['key1' => 1]], ''],
            [null, 'The value must be listable array type'],
            [1, 'The value must be listable array type'],
            [['key1' => 1], 'The value must be listable array type'],
            [['key1' => 1, 2], 'The value must be listable array type'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new IsListableArrayRule('test_key', false, 'Error');
        $this->assertEquals('Error', $rule->check(null));
    }
}
