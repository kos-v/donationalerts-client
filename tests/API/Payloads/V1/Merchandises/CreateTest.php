<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Payloads\V1\Merchandises;

use function implode;
use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Create;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use PHPUnit\Framework\TestCase;
use function sprintf;
use function str_repeat;

final class CreateTest extends TestCase
{
    public function testGetFields(): void
    {
        $payloadRawData = [
            Create::F_MERCHANT_IDENTIFIER => 'merchant_id',
            Create::F_MERCHANDISE_IDENTIFIER => 'merchandise_id',
            Create::F_TITLE => [
                LangEnum::ENGLISH_USA => 'Title',
                LangEnum::GERMAN => 'Titel'
            ],
            Create::F_IS_ACTIVE => 1,
            Create::F_IS_PERCENTAGE => 1,
            Create::F_CURRENCY => CurrencyEnum::USD,
            Create::F_PRICE_USER => 25.7,
            Create::F_PRICE_SERVICE => 10.3,
            Create::F_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999',
            Create::F_IMG_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999/img.png',
            Create::F_END_AT_TS => 1687357916,
        ];

        $this->assertEquals($payloadRawData, (new Create($payloadRawData))->getFields());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new Create($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): array
    {
        $correctSample = new ImmutableArrayObject([
            Create::F_MERCHANT_IDENTIFIER => 'merchant_id',
            Create::F_MERCHANDISE_IDENTIFIER => 'merchandise_id',
            Create::F_TITLE => [
                LangEnum::ENGLISH_USA => 'Title',
                LangEnum::GERMAN => 'Titel'
            ],
            Create::F_IS_ACTIVE => 1,
            Create::F_IS_PERCENTAGE => 1,
            Create::F_CURRENCY => CurrencyEnum::USD,
            Create::F_PRICE_USER => 25.7,
            Create::F_PRICE_SERVICE => 10.3,
            Create::F_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999',
            Create::F_IMG_URL => 'https://example.local/product_id=merchandise_id&user_id=99999999/img.png',
            Create::F_END_AT_TS => 1687357916,
        ]);

        return [
            [
                $correctSample->unset(
                    Create::F_MERCHANT_IDENTIFIER,
                    Create::F_MERCHANDISE_IDENTIFIER,
                    Create::F_TITLE,
                    Create::F_CURRENCY,
                    Create::F_PRICE_USER,
                    Create::F_PRICE_SERVICE,
                )->toArray(),
                sprintf('The field [*] is not valid. Required fields [%s] are not set', implode(', ', [
                    Create::F_MERCHANT_IDENTIFIER,
                    Create::F_MERCHANDISE_IDENTIFIER,
                    Create::F_TITLE,
                    Create::F_CURRENCY,
                    Create::F_PRICE_USER,
                    Create::F_PRICE_SERVICE,
                ]))
            ],
            [
                $correctSample->set([Create::F_MERCHANT_IDENTIFIER => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Create::F_MERCHANT_IDENTIFIER
                )
            ],
            [
                $correctSample->set([Create::F_MERCHANDISE_IDENTIFIER => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Create::F_MERCHANDISE_IDENTIFIER
                )
            ],
            [
                $correctSample->set([Create::F_TITLE => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the array type',
                    Create::F_TITLE
                )
            ],
            [
                $correctSample->set([Create::F_IS_ACTIVE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Create::F_IS_ACTIVE
                )
            ],
            [
                $correctSample->set([Create::F_IS_PERCENTAGE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Create::F_IS_PERCENTAGE
                )
            ],
            [
                $correctSample->set([Create::F_CURRENCY => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Create::F_CURRENCY
                )
            ],
            [
                $correctSample->set([Create::F_PRICE_USER => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the numeric type',
                    Create::F_PRICE_USER
                )
            ],
            [
                $correctSample->set([Create::F_PRICE_SERVICE => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the numeric type',
                    Create::F_PRICE_SERVICE
                )
            ],
            [
                $correctSample->set([Create::F_URL => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Create::F_URL
                )
            ],
            [
                $correctSample->set([Create::F_IMG_URL => 0])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the string type',
                    Create::F_IMG_URL
                )
            ],
            [
                $correctSample->set([Create::F_END_AT_TS => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value does not match the integer type',
                    Create::F_END_AT_TS
                )
            ],
            [
                $correctSample->set([Create::F_MERCHANDISE_IDENTIFIER => ''])->toArray(),
                'The length of the value string must be at least 1 and not more than 16'
            ],
            [
                $correctSample->set([Create::F_MERCHANDISE_IDENTIFIER => str_repeat('X', 17)])->toArray(),
                'The length of the value string must be at least 1 and not more than 16'
            ],
            [
                $correctSample->unset(Create::F_TITLE . '.' . LangEnum::ENGLISH_USA)->toArray(),
                sprintf(
                    'The field %s is not valid. Required fields [%s] are not set',
                    Create::F_TITLE,
                    LangEnum::ENGLISH_USA
                )
            ],
            [
                $correctSample->set([Create::F_TITLE . '.not_valid_key' => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The key is not in the list of allowed array keys. Allowed keys: [%s]',
                    Create::F_TITLE,
                    implode(', ', LangEnum::getAll())
                )
            ],
            [
                $correctSample->set([Create::F_TITLE . '.' . LangEnum::GERMAN => 1])->toArray(),
                sprintf('The field %s.* is not valid. The value does not match the string type', Create::F_TITLE)
            ],
            [
                $correctSample->set([Create::F_TITLE . '.' . LangEnum::GERMAN => ''])->toArray(),
                sprintf(
                    'The field %s.* is not valid. The length of the value string must be at least 1 and not more than 1024',
                    Create::F_TITLE
                )
            ],
            [
                $correctSample->set([Create::F_TITLE . '.' . LangEnum::GERMAN => str_repeat('X', 1025)])->toArray(),
                sprintf(
                    'The field %s.* is not valid. The length of the value string must be at least 1 and not more than 1024',
                    Create::F_TITLE
                )
            ],
            [
                $correctSample->set([Create::F_IS_ACTIVE => 2])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [0, 1]',
                    Create::F_IS_ACTIVE
                )
            ],
            [
                $correctSample->set([Create::F_IS_PERCENTAGE => 2])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [0, 1]',
                    Create::F_IS_PERCENTAGE
                )
            ],
            [
                $correctSample->set([Create::F_CURRENCY => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [%s]',
                    Create::F_CURRENCY,
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
                $correctSample->set([Create::F_URL => str_repeat('X', 129)])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 0 and not more than 128',
                    Create::F_URL
                )
            ],
            [
                $correctSample->set([Create::F_IMG_URL => str_repeat('X', 129)])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 0 and not more than 128',
                    Create::F_IMG_URL
                )
            ]
        ];
    }
}
