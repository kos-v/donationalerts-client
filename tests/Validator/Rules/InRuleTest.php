<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\InRule;
use PHPUnit\Framework\TestCase;

final class InRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(array $allowedValues, $value, string $expectedMsg): void
    {
        $rule = new InRule('test_key', $allowedValues);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [['a'], 'a', ''],
            [['a', 'b'], 'b', ''],
            [[3, null, 'a'], null, ''],
            [[], 'a', 'The value is not in the list of allowed values. Allowed values: []'],
            [['a'], 'b', 'The value is not in the list of allowed values. Allowed values: [a]'],
            [['a', 'b'], 'c', 'The value is not in the list of allowed values. Allowed values: [a, b]'],
            [['a', null], 3, 'The value is not in the list of allowed values. Allowed values: [a, null]'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new InRule('test_key', ['a', 'b'], false, 'Error {{allowedValues}}');
        $this->assertEquals('Error a, b', $rule->check(null));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new InRule('test_key', ['a', 'b'], true);
        $this->assertEquals('', $rule->check(null));
    }
}
