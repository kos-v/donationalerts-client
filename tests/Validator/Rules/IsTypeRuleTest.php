<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use PHPUnit\Framework\TestCase;

final class IsTypeRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(string $tyoe, $value, string $expectedMsg): void
    {
        $rule = new IsTypeRule('test_key', $tyoe);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            ['array', [], ''],
            ['boolean', true, ''],
            ['double', 0.3, ''],
            ['integer', 0, ''],
            ['null', null, ''],
            ['numeric', 0, ''],
            ['numeric', 0.3, ''],
            ['string', '', ''],
            ['array', '', 'The value does not match the array type'],
            ['boolean', '', 'The value does not match the boolean type'],
            ['double', '', 'The value does not match the double type'],
            ['integer', '', 'The value does not match the integer type'],
            ['null', '', 'The value does not match the null type'],
            ['numeric', '', 'The value does not match the numeric type'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new IsTypeRule('test_key', 'integer', false, 'Error {{expectedType}}');
        $this->assertEquals('Error integer', $rule->check(''));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new IsTypeRule('test_key', 'integer', true);
        $this->assertEquals('', $rule->check(null));
    }
}
