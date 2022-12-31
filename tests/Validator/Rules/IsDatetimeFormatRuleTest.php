<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rules\DatetimeFormatRule;
use PHPUnit\Framework\TestCase;

final class DatetimeFormatRuleTest extends TestCase
{
    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(string $format, $value, string $expectedMsg): void
    {
        $rule = new DatetimeFormatRule('test_key', $format);
        $this->assertEquals($expectedMsg, $rule->check($value));
    }

    public function checkDataProvider(): array
    {
        return [
            ['Y-m-d', '1970-01-31', ''],
            ['H:i:s', '23:59:59', ''],
            ['Y-m-d H:i:s', '1970-01-31 23:59:59', ''],
            ['Y-m-d H:i:s', null, 'The datetime must be specified in the format Y-m-d H:i:s'],
            ['Y-m-d H:i:s', 1, 'The datetime must be specified in the format Y-m-d H:i:s'],
            ['Y-m-d H:i:s', '', 'The datetime must be specified in the format Y-m-d H:i:s'],
            ['Y-m-d H:i:s', '23:59:59 1970-01-31', 'The datetime must be specified in the format Y-m-d H:i:s'],
        ];
    }

    public function testCheckWithoutDefaultErrMessage(): void
    {
        $rule = new DatetimeFormatRule('test_key', 'Y-m-d', false, 'Error {{format}}');
        $this->assertEquals('Error Y-m-d', $rule->check(null));
    }

    public function testCheckWhenNullableTrue(): void
    {
        $rule = new DatetimeFormatRule('test_key', 'Y-m-d', true);
        $this->assertEquals('', $rule->check(null));
    }
}
