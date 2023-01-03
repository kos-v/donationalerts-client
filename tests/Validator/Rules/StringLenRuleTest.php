<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\Rules\StringLenRule;
use PHPUnit\Framework\TestCase;

final class StringLenRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(int $min, int $max, $value, string $expectedMsg): void
    {
        $rule = new StringLenRule('test_key', $min, $max);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [0, 0, '', ''],
            [0, 1, '', ''],
            [1, 1, 'a', ''],
            [1, 3, 'ab', ''],
            [1, 3, 'abc', ''],
            [0, 0, 'a', 'The length of the value string must be at least 0 and not more than 0'],
            [1, 3, '', 'The length of the value string must be at least 1 and not more than 3'],
            [1, 3, 'abcd', 'The length of the value string must be at least 1 and not more than 3'],
        ];
    }

    public function testCheckWhenValueTypeNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument $value must be string type');

        $rule = new StringLenRule('test_key', 1, 3);
        $rule->check(1);
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new StringLenRule('test_key', 1, 3, false, 'Error {{min}} {{max}}');
        $this->assertEquals('Error 1 3', $rule->check(''));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new StringLenRule('test_key', 1, 3, true, 'Error {{min}} {{max}}');
        $this->assertEquals('', $rule->check(null));
    }
}
