<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Merchandises;

use function array_map;
use DateTimeImmutable;
use function implode;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\CreateUpdate;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\Merchant;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\Title;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;
use function sprintf;

final class CreateUpdateTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource1 = new CreateUpdate([
            'id' => 3,
            'merchant' => [
                'identifier' => 'test_identifier',
                'name' => 'test_name',
            ],
            'identifier' => '9999999',
            'title' => [
                LangEnum::ENGLISH_USA => 'Title en',
                LangEnum::GERMAN => 'Title de',
            ],
            'is_active' => 1,
            'is_percentage' => 0,
            'currency' => CurrencyEnum::USD,
            'price_user' => 30.5,
            'price_service' => 15.7,
            'url' => 'http:/example.local/?product_id=9999999',
            'img_url' => 'http:/example.local/img.png',
            'end_at' => '2020-12-31 23.59.59'
        ]);
        $this->assertEquals(3, $resource1->getId());
        $this->assertEquals(
            new Merchant([
                'identifier' => 'test_identifier',
                'name' => 'test_name',
            ]),
            $resource1->getMerchant()
        );
        $this->assertEquals('9999999', $resource1->getIdentifier());
        $this->assertEquals(
            new Title([
                LangEnum::ENGLISH_USA => 'Title en',
                LangEnum::GERMAN => 'Title de',
            ]),
            $resource1->getTitle()
        );
        $this->assertEquals(1, $resource1->getIsActive());
        $this->assertEquals(0, $resource1->getIsPercentage());
        $this->assertEquals(CurrencyEnum::USD, $resource1->getCurrency());
        $this->assertEquals(30.5, $resource1->getPriceUser());
        $this->assertEquals(15.7, $resource1->getPriceService());
        $this->assertEquals('http:/example.local/?product_id=9999999', $resource1->getUrl());
        $this->assertEquals('http:/example.local/img.png', $resource1->getImgUrl());
        $this->assertInstanceOf(DateTimeImmutable::class, $resource1->getEndAt());
        $this->assertEquals('2020-12-31 23.59.59', $resource1->getEndAt()->format('Y-m-d H.i.s'));

        $resource2 = new CreateUpdate([
            'id' => 3,
            'merchant' => [
                'identifier' => 'test_identifier',
                'name' => 'test_name',
            ],
            'identifier' => '9999999',
            'title' => [
                LangEnum::ENGLISH_USA => 'Title en',
                LangEnum::GERMAN => 'Title de',
            ],
            'is_active' => 1,
            'is_percentage' => 0,
            'currency' => CurrencyEnum::USD,
            'price_user' => 30.5,
            'price_service' => 15.7,
            'url' => null,
            'img_url' => null,
            'end_at' => null,
        ]);
        $this->assertNull($resource2->getUrl());
        $this->assertNull($resource2->getImgUrl());
        $this->assertNull($resource2->getEndAt());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new CreateUpdate($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): iterable
    {
        $correctSample = new ImmutableArrayObject([
            'id' => 3,
            'merchant' => [
                'identifier' => 'test_identifier',
                'name' => 'test_name',
            ],
            'identifier' => '9999999',
            'title' => [
                LangEnum::ENGLISH_USA => 'Title en',
                LangEnum::GERMAN => 'Title de',
            ],
            'is_active' => 1,
            'is_percentage' => 0,
            'currency' => CurrencyEnum::USD,
            'price_user' => 30.5,
            'price_service' => 15.7,
            'url' => 'http:/example.local/?product_id=9999999',
            'img_url' => 'http:/example.local/img.png',
            'end_at' => '2020-12-31 23.59.59'
        ]);


        yield [
            $correctSample->set([1])->toArray(),
            'Content of CreateUpdate resource is not valid. Error: "[*]":"The value must be keyable array type"'
        ];

        yield from array_map(
            static fn ($key) => [
                $correctSample->unset($key)->toArray(),
                sprintf(
                    'Content of CreateUpdate resource is not valid. Error: "[*]":"Required fields [%s] are not set"',
                    $key
                )
            ],
            [
                'id', 'merchant', 'identifier', 'title',
                'is_active', 'is_percentage', 'currency', 'price_user',
                'price_service', 'url', 'img_url', 'end_at',
            ]
        );

        yield from array_map(
            static fn (array $item) => [
                $correctSample->set([$item['key'] => $item['setVal']])->toArray(),
                sprintf(
                    'Content of CreateUpdate resource is not valid. Error: "%s":"The value does not match the %s type"',
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
                    'key' => 'merchant',
                    'type' => 'object',
                    'setVal' => 1
                ],
                [
                    'key' => 'identifier',
                    'type' => 'string',
                    'setVal' => 1
                ],
                [
                    'key' => 'title',
                    'type' => 'object',
                    'setVal' => 1
                ],
                [
                    'key' => 'is_active',
                    'type' => 'integer',
                    'setVal' => 'str',
                ],
                [
                    'key' => 'is_percentage',
                    'type' => 'integer',
                    'setVal' => 'str',
                ],
                [
                    'key' => 'currency',
                    'type' => 'string',
                    'setVal' => 1,
                ],
                [
                    'key' => 'price_user',
                    'type' => 'numeric',
                    'setVal' => 'str',
                ],
                [
                    'key' => 'price_service',
                    'type' => 'numeric',
                    'setVal' => 'str',
                ],
                [
                    'key' => 'url',
                    'type' => 'string',
                    'setVal' => 1,
                ],
                [
                    'key' => 'img_url',
                    'type' => 'string',
                    'setVal' => 1,
                ],
                [
                    'key' => 'end_at',
                    'type' => 'string',
                    'setVal' => 1,
                ],
            ]
        );

        yield from [
            [
                $correctSample->set(['is_active' => 2])->toArray(),
                'Content of CreateUpdate resource is not valid. Error: "is_active":"The value is not in the list of allowed values. Allowed values: [0, 1]"'
            ],
            [
                $correctSample->set(['is_percentage' => 2])->toArray(),
                'Content of CreateUpdate resource is not valid. Error: "is_percentage":"The value is not in the list of allowed values. Allowed values: [0, 1]"'
            ],
            [
                $correctSample->set(['currency' => 'not_valid'])->toArray(),
                sprintf(
                    'Content of CreateUpdate resource is not valid. Error: "currency":"The value is not in the list of allowed values. Allowed values: [%s]"',
                    implode(', ', CurrencyEnum::getAll())
                )
            ],
            [
                $correctSample->set(['end_at' => '23:59:59 2020-12-31'])->toArray(),
                'Content of CreateUpdate resource is not valid. Error: "end_at":"The datetime must be specified in the format Y-m-d H.i.s"',
            ],
        ];
    }
}
