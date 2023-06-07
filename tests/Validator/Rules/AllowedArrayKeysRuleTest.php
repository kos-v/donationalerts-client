<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\AllowedArrayKeysRule;
use PHPUnit\Framework\TestCase;

final class AllowedArrayKeysRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(array $allowedKeys, array $value, string $expectedMsg): void
    {
        $rule = new AllowedArrayKeysRule('test_key', $allowedKeys);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            [[], [], ''],
            [['a'], ['a' => 1], ''],
            [['a', 'b'], ['a' => 1, 'b' => 2], ''],
            [[0, 'a', 1], [1, 'a' => 2, 3], ''],
            [[], ['a' => 1], 'The key is not in the list of allowed array keys. Allowed keys: []'],
            [['a'], ['b' => 1], 'The key is not in the list of allowed array keys. Allowed keys: [a]'],
            [['a', 1], [0, 'a' => 1], 'The key is not in the list of allowed array keys. Allowed keys: [a, 1]'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new AllowedArrayKeysRule('test_key', ['a', 'b'], false, 'Error {{allowedKeys}}');
        $this->assertEquals('Error a, b', $rule->check(['c']));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new AllowedArrayKeysRule('test_key', ['a', 'b'], true);
        $this->assertEquals('', $rule->check(null));
    }
}
