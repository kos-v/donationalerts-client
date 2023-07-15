<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Payloads\V1\Alerts;

use function array_map;
use function implode;
use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\SendMerchandiseSale;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use PHPUnit\Framework\TestCase;
use function sprintf;
use function str_repeat;

final class SendMerchandiseSaleTest extends TestCase
{
    public function testGetFields(): void
    {
        $payloadRawData1 = [
            SendMerchandiseSale::F_USER_ID => 999999999,
            SendMerchandiseSale::F_AMOUNT => 15.7,
            SendMerchandiseSale::F_CURRENCY => CurrencyEnum::USD,
            SendMerchandiseSale::F_MERCHANT_IDENTIFIER => 'merchant_identifier',
            SendMerchandiseSale::F_MERCHANDISE_IDENTIFIER => 'merchandise_identifier',
            SendMerchandiseSale::F_EXTERNAL_ID => 'external_id',
            SendMerchandiseSale::F_BOUGHT_AMOUNT => 2,
            SendMerchandiseSale::F_USERNAME => 'User',
            SendMerchandiseSale::F_MESSAGE => 'Message',
        ];
        $this->assertEquals($payloadRawData1, (new SendMerchandiseSale($payloadRawData1))->getFields());

        $payloadRawData2 = [
            SendMerchandiseSale::F_USER_ID => 999999999,
            SendMerchandiseSale::F_AMOUNT => 15.7,
            SendMerchandiseSale::F_CURRENCY => CurrencyEnum::USD,
            SendMerchandiseSale::F_MERCHANT_IDENTIFIER => 'merchant_identifier',
            SendMerchandiseSale::F_MERCHANDISE_IDENTIFIER => 'merchandise_identifier',
            SendMerchandiseSale::F_EXTERNAL_ID => 'external_id',
        ];
        $this->assertEquals($payloadRawData2, (new SendMerchandiseSale($payloadRawData2))->getFields());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new SendMerchandiseSale($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): iterable
    {
        $correctSample = new ImmutableArrayObject([
            SendMerchandiseSale::F_USER_ID => 999999999,
            SendMerchandiseSale::F_AMOUNT => 15.7,
            SendMerchandiseSale::F_CURRENCY => CurrencyEnum::USD,
            SendMerchandiseSale::F_MERCHANT_IDENTIFIER => 'merchant_identifier',
            SendMerchandiseSale::F_MERCHANDISE_IDENTIFIER => 'merchandise_identifier',
            SendMerchandiseSale::F_EXTERNAL_ID => 'external_id',
            SendMerchandiseSale::F_BOUGHT_AMOUNT => 2,
            SendMerchandiseSale::F_USERNAME => 'User',
            SendMerchandiseSale::F_MESSAGE => 'Message',
        ]);

        yield from array_map(static fn (string $key) => [
            $correctSample->unset($key)->toArray(),
            sprintf(
                'The field [*] is not valid. Required fields [%s] are not set',
                $key
            )
        ], [
            SendMerchandiseSale::F_USER_ID,
            SendMerchandiseSale::F_EXTERNAL_ID,
            SendMerchandiseSale::F_MERCHANT_IDENTIFIER,
            SendMerchandiseSale::F_MERCHANDISE_IDENTIFIER,
            SendMerchandiseSale::F_AMOUNT,
            SendMerchandiseSale::F_CURRENCY
        ]);

        yield from array_map(static fn (array $item) => [
            $correctSample->set([$item['key'] => $item['setVal']])->toArray(),
            sprintf(
                'The field %s is not valid. The value does not match the %s type',
                $item['key'],
                $item['type'],
            )
        ], [
            [
                'key' => SendMerchandiseSale::F_USER_ID,
                'type' => 'integer',
                'setVal' => 'str'
            ],
            [
                'key' => SendMerchandiseSale::F_EXTERNAL_ID,
                'type' => 'string',
                'setVal' => 1
            ],
            [
                'key' => SendMerchandiseSale::F_MERCHANT_IDENTIFIER,
                'type' => 'string',
                'setVal' => 1
            ],
            [
                'key' => SendMerchandiseSale::F_MERCHANDISE_IDENTIFIER,
                'type' => 'string',
                'setVal' => 1
            ],
            [
                'key' => SendMerchandiseSale::F_AMOUNT,
                'type' => 'numeric',
                'setVal' => 'str'
            ],
            [
                'key' => SendMerchandiseSale::F_CURRENCY,
                'type' => 'string',
                'setVal' => 1
            ],
            [
                'key' => SendMerchandiseSale::F_BOUGHT_AMOUNT,
                'type' => 'integer',
                'setVal' => 'str'
            ],
            [
                'key' => SendMerchandiseSale::F_USERNAME,
                'type' => 'string',
                'setVal' => 1
            ],
            [
                'key' => SendMerchandiseSale::F_MESSAGE,
                'type' => 'string',
                'setVal' => 1
            ],
        ]);

        yield from [
            [
                $correctSample->set([SendMerchandiseSale::F_EXTERNAL_ID => ''])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 1 and not more than 32',
                    SendMerchandiseSale::F_EXTERNAL_ID
                )
            ],
            [
                $correctSample->set([SendMerchandiseSale::F_EXTERNAL_ID => str_repeat('X', 33)])->toArray(),
                sprintf(
                    'The field %s is not valid. The length of the value string must be at least 1 and not more than 32',
                    SendMerchandiseSale::F_EXTERNAL_ID
                )
            ],
            [
                $correctSample->set([SendMerchandiseSale::F_CURRENCY => 'str'])->toArray(),
                sprintf(
                    'The field %s is not valid. The value is not in the list of allowed values. Allowed values: [%s]',
                    SendMerchandiseSale::F_CURRENCY,
                    implode(', ', [
                        CurrencyEnum::EUR,
                        CurrencyEnum::USD,
                        CurrencyEnum::RUB,
                        CurrencyEnum::BRL,
                        CurrencyEnum::TRY,
                    ])
                )
            ],
        ];
    }
}
