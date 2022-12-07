<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use PHPUnit\Framework\TestCase;

final class RequiredFieldRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(array $requiredFields, $value, string $expectedMsg): void
    {
        $rule = new RequiredFieldRule('test_key', $requiredFields);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [[], [], ''],
            [[], ['a' => 1, 'b' => 2], ''],
            [['a'], ['a' => 1, 'b' => 2], ''],
            [['a', 'b'], ['a' => 1, 'b' => 2], ''],
            [['a'], [], 'Required fields [a] are not set'],
            [['c'], ['a' => 1, 'b' => 2], 'Required fields [c] are not set'],
            [['c', 'd'], ['a' => 1, 'b' => 2], 'Required fields [c, d] are not set']
        ];
    }

    public function testCheckWhenValueTypeNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument $value must be contains an array');

        $rule = new RequiredFieldRule('test_key', ['a', 'b']);
        $rule->check('');
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new RequiredFieldRule('test_key', ['a', 'b'], false, 'Error {{notFoundFields}}');
        $this->assertEquals('Error a, b', $rule->check([]));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new RequiredFieldRule('test_key', ['a', 'b'], true);
        $this->assertEquals('', $rule->check(null));
    }
}
