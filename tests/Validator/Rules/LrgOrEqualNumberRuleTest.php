<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\Rules\LrgOrEqualNumberRule;
use PHPUnit\Framework\TestCase;

final class LrgOrEqualNumberRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(int $min, $value, string $expectedMsg): void
    {
        $rule = new LrgOrEqualNumberRule('test_key', $min);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [0, 0, ''],
            [0, 1, ''],
            [3, 7, ''],
            [0, -1, 'The value must be larger than or equal to 0'],
            [3, 2, 'The value must be larger than or equal to 3'],
        ];
    }

    public function testCheckWhenValueTypeNotNumeric(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The $value argument must be a numeric type');

        $rule = new LrgOrEqualNumberRule('test_key', 0);
        $rule->check('');
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new LrgOrEqualNumberRule('test_key', 0, false, 'Error {{min}}');
        $this->assertEquals('Error 0', $rule->check(-1));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new LrgOrEqualNumberRule('test_key', 0, true);
        $this->assertEquals('', $rule->check(null));
    }
}
