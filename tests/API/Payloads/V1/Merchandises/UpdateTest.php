<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Payloads\V1\Merchandises;

use function implode;
use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Update;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use PHPUnit\Framework\TestCase;
use function sprintf;
use function str_repeat;

final class UpdateTest extends TestCase
{
    /**
     * @dataProvider getFieldsDataProvider
     */
    public function testGetFields(array $payloadRawData): void
    {
        $this->assertEquals($payloadRawData, (new Update($payloadRawData))->getFields());
    }

    public function getFieldsDataProvider(): array
    {
        return [
            [[
                Update::F_MERCHANT_IDENTIFIER => 'merchant_id'
            ]],
            [[
                Update::F_MERCHANDISE_IDENTIFIER => 'merchandise_id'
            ]],
            [[
                Update::F_TITLE => [
                    LangEnum::ENGLISH_USA => 'Title',
                    LangEnum::GERMAN => 'Titel'
                ]
            ]],
            [[
                Update::F_IS_ACTIVE => 1
            ]],
            [[
                Update::F_IS_PERCENTAGE => 1
            ]],
            [[
                Update::F_CURRENCY => CurrencyEnum::USD
            ]],
            [[
                Update::F_PRICE_USER => 25.7
            ]],
            [[
                Update::F_PRICE_SERVICE => 10.3
            ]],
            [[
                Update::F_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999'
            ]],
            [[
                Update::F_IMG_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999/img.png'
            ]],
            [[
                Update::F_END_AT_TS => 1687357916
            ]],
            [[
                Update::F_MERCHANT_IDENTIFIER => 'merchant_id',
                Update::F_MERCHANDISE_IDENTIFIER => 'merchandise_id',
                Update::F_TITLE => [
                    LangEnum::ENGLISH_USA => 'Title',
                    LangEnum::GERMAN => 'Titel'
                ],
                Update::F_IS_ACTIVE => 1,
                Update::F_IS_PERCENTAGE => 1,
                Update::F_CURRENCY => CurrencyEnum::USD,
                Update::F_PRICE_USER => 25.7,
                Update::F_PRICE_SERVICE => 10.3,
                Update::F_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999',
                Update::F_IMG_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999/img.png',
                Update::F_END_AT_TS => 1687357916
            ]],
        ];
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new Update($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): array
    {
        $correctSample = new ImmutableArrayObject([]);

        return [
            [
                $correctSample->set([Update::F_MERCHANT_IDENTIFIER => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Update::F_MERCHANT_IDENTIFIER
                )
            ],
            [
                $correctSample->set([Update::F_MERCHANDISE_IDENTIFIER => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Update::F_MERCHANDISE_IDENTIFIER
                )
            ],
            [
                $correctSample->set([Update::F_TITLE => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the array type',
                    Update::F_TITLE
                )
            ],
            [
                $correctSample->set([Update::F_IS_ACTIVE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Update::F_IS_ACTIVE
                )
            ],
            [
                $correctSample->set([Update::F_IS_PERCENTAGE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Update::F_IS_PERCENTAGE
                )
            ],
            [
                $correctSample->set([Update::F_CURRENCY => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Update::F_CURRENCY
                )
            ],
            [
                $correctSample->set([Update::F_PRICE_USER => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the numeric type',
                    Update::F_PRICE_USER
                )
            ],
            [
                $correctSample->set([Update::F_PRICE_SERVICE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the numeric type',
                    Update::F_PRICE_SERVICE
                )
            ],
            [
                $correctSample->set([Update::F_URL => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Update::F_URL
                )
            ],
            [
                $correctSample->set([Update::F_IMG_URL => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Update::F_IMG_URL
                )
            ],
            [
                $correctSample->set([Update::F_END_AT_TS => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Update::F_END_AT_TS
                )
            ],
            [
                $correctSample->set([Update::F_MERCHANDISE_IDENTIFIER => ''])->toArray(),
                'The length of the value string must be at least 1 and not more than 16'
            ],
            [
                $correctSample->set([Update::F_MERCHANDISE_IDENTIFIER => str_repeat('X', 17)])->toArray(),
                'The length of the value string must be at least 1 and not more than 16'
            ],
            [
                $correctSample->set([
                    Update::F_TITLE . '.' . LangEnum::ENGLISH_USA => 'Title',
                    Update::F_TITLE . '.not_valid_key' => 'str'
                ])->toArray(),
                sprintf(
                    'The field %s is not valid. The key is not in the list of allowed array keys. Allowed keys: [%s]',
                    Update::F_TITLE,
                    implode(', ', LangEnum::getAll())
                )
            ],
            [
                $correctSample->set([
                    Update::F_TITLE . '.' . LangEnum::ENGLISH_USA => 'Title',
                    Update::F_TITLE . '.' . LangEnum::GERMAN => 1
                ])->toArray(),
                sprintf('The field %s.* is not valid. The value does not match the string type', Update::F_TITLE)
            ],
            [
                $correctSample->set([
                    Update::F_TITLE . '.' . LangEnum::ENGLISH_USA => 'Title',
                    Update::F_TITLE . '.' . LangEnum::GERMAN => ''
                ])->toArray(),
                sprintf(
                    'The field %s.* is not valid. The length of the value string must be at least 1 and not more than 1024',
                    Update::F_TITLE
                )
            ],
            [
                $correctSample->set([
                    Update::F_TITLE . '.' . LangEnum::ENGLISH_USA => 'Title',
                    Update::F_TITLE . '.' . LangEnum::GERMAN => str_repeat('X', 1025)
                ])->toArray(),
                sprintf(
                    'The field %s.* is not valid. The length of the value string must be at least 1 and not more than 1024',
                    Update::F_TITLE
                )
            ],
            [
                $correctSample->set([Update::F_IS_ACTIVE => 2])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [0, 1]',
                    Update::F_IS_ACTIVE
                )
            ],
            [
                $correctSample->set([Update::F_IS_PERCENTAGE => 2])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [0, 1]',
                    Update::F_IS_PERCENTAGE
                )
            ],
            [
                $correctSample->set([Update::F_CURRENCY => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [%s]',
                    Update::F_CURRENCY,
                    implode(', ', [
                        CurrencyEnum::EUR,
                        CurrencyEnum::USD,
                        CurrencyEnum::RUB,
                        CurrencyEnum::BRL,
                        CurrencyEnum::TRY,
                    ])
                )
            ],
            [
                $correctSample->set([Update::F_URL => str_repeat('X', 129)])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 0 and not more than 128',
                    Update::F_URL
                )
            ],
            [
                $correctSample->set([Update::F_IMG_URL => str_repeat('X', 129)])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 0 and not more than 128',
                    Update::F_IMG_URL
                )
            ]
        ];
    }
}
