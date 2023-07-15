<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Alerts;

use function array_map;
use function implode;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\SendMerchandiseSale;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;
use function sprintf;

final class SendMerchandiseSaleTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource1 = new SendMerchandiseSale([
            'id' => 9999999,
            'name' => 'Merchandise-sale',
            'external_id' => 'ExternalId',
            'username' => 'User',
            'message' => 'Message',
            'amount' => 15.7,
            'currency' => CurrencyEnum::USD,
            'bought_amount' => 2,
            'created_at' => '2020-11-15 11.36.14',
            'is_shown' => 0,
            'shown_at' => '2020-11-15 12.59.59'
        ]);
        $this->assertEquals(9999999, $resource1->getId());
        $this->assertEquals('Merchandise-sale', $resource1->getName());
        $this->assertEquals('ExternalId', $resource1->getExternalId());
        $this->assertEquals('User', $resource1->getUsername());
        $this->assertEquals('Message', $resource1->getMessage());
        $this->assertEquals(15.7, $resource1->getAmount());
        $this->assertEquals(CurrencyEnum::USD, $resource1->getCurrency());
        $this->assertEquals(2, $resource1->getBoughtAmount());
        $this->assertEquals('2020-11-15 11.36.14', $resource1->getCreatedAt()->format('Y-m-d H.i.s'));
        $this->assertEquals(0, $resource1->getIsShown());
        $this->assertEquals('2020-11-15 12.59.59', $resource1->getShownAt()->format('Y-m-d H.i.s'));

        $resource2 = new SendMerchandiseSale([
            'id' => 9999999,
            'name' => 'Merchandise-sale',
            'external_id' => 'ExternalId',
            'username' => null,
            'message' => null,
            'amount' => 15.7,
            'currency' => CurrencyEnum::USD,
            'bought_amount' => 2,
            'created_at' => '2020-11-15 11.36.14',
            'is_shown' => 0,
            'shown_at' => null
        ]);
        $this->assertNull($resource2->getUsername());
        $this->assertNull($resource2->getMessage());
        $this->assertNull($resource2->getShownAt());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new SendMerchandiseSale($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): iterable
    {
        $correctSample = new ImmutableArrayObject([
            'id' => 9999999,
            'name' => 'Merchandise-sale',
            'external_id' => 'ExternalId',
            'username' => 'User',
            'message' => 'Message',
            'amount' => 15.7,
            'currency' => CurrencyEnum::USD,
            'bought_amount' => 2,
            'created_at' => '2020-11-15 11.36.14',
            'is_shown' => 0,
            'shown_at' => '2020-11-15 12.59.59'
        ]);


        yield [
            $correctSample->set([1])->toArray(),
            'Content of SendMerchandiseSale resource is not valid. Error: "[*]":"The value must be keyable array type"'
        ];

        yield from array_map(
            static fn ($key) => [
                $correctSample->unset($key)->toArray(),
                sprintf(
                    'Content of SendMerchandiseSale resource is not valid. Error: "[*]":"Required fields [%s] are not set"',
                    $key
                )
            ],
            [
                'id', 'name', 'external_id', 'username', 'message', 'amount',
                'currency', 'bought_amount', 'is_shown', 'created_at', 'shown_at'
            ]
        );

        yield from array_map(
            static fn (array $item) => [
                $correctSample->set([$item['key'] => $item['setVal']])->toArray(),
                sprintf(
                    'Content of SendMerchandiseSale resource is not valid. Error: "%s":"The value does not match the %s type"',
                    $item['key'],
                    $item['type']
                )
            ],
            [
                [
                    'key' => 'id',
                    'type' => 'integer',
                    'setVal' => 'str'
                ],
                [
                    'key' => 'name',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'external_id',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'username',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'message',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'amount',
                    'type' => 'numeric',
                    'setVal' => 'str'
                ],
                [
                    'key' => 'currency',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'bought_amount',
                    'type' => 'integer',
                    'setVal' => 'str'
                ],
                [
                    'key' => 'is_shown',
                    'type' => 'integer',
                    'setVal' => 'str'
                ],
                [
                    'key' => 'created_at',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'created_at',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'shown_at',
                    'type' => 'string',
                    'setVal' => 1
                ],
            ]
        );

        yield from [
            [
                $correctSample->set(['is_shown' => 2])->toArray(),
                'Content of SendMerchandiseSale resource is not valid. Error: "is_shown":"The value is not in the list of allowed values. Allowed values: [0, 1]"'
            ],
            [
                $correctSample->set(['currency' => 'not_valid'])->toArray(),
                sprintf(
                    'Content of SendMerchandiseSale resource is not valid. Error: "currency":"The value is not in the list of allowed values. Allowed values: [%s]"',
                    implode(', ', CurrencyEnum::getAll())
                )
            ],
            [
                $correctSample->set(['created_at' => '23:59:59 2020-12-31'])->toArray(),
                'Content of SendMerchandiseSale resource is not valid. Error: "created_at":"The datetime must be specified in the format Y-m-d H.i.s"',
            ],
            [
                $correctSample->set(['shown_at' => '23:59:59 2020-12-31'])->toArray(),
                'Content of SendMerchandiseSale resource is not valid. Error: "shown_at":"The datetime must be specified in the format Y-m-d H.i.s"',
            ],
        ];
    }
}
