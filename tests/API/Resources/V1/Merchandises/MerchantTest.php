<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Merchandises;

use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\Merchant;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class MerchantTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource = new Merchant([
            'identifier' => 'test_identifier',
            'name' => 'test_name',
        ]);

        $this->assertEquals('test_identifier', $resource->getIdentifier());
        $this->assertEquals('test_name', $resource->getName());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new Merchant($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): array
    {
        $correctSample = new ImmutableArrayObject([
            'identifier' => 'test_identifier',
            'name' => 'test_name',
        ]);

        return [
            [
                $correctSample->set([1])->toArray(),
                'Content of Merchant resource is not valid. Error: "[*]":"The value must be keyable array type"'
            ],
            [
                $correctSample->unset('identifier')->toArray(),
                'Content of Merchant resource is not valid. Error: "[*]":"Required fields [identifier] are not set"'
            ],
            [
                $correctSample->unset('name')->toArray(),
                'Content of Merchant resource is not valid. Error: "[*]":"Required fields [name] are not set"'
            ],
            [
                $correctSample->set(['identifier' => 1])->toArray(),
                'Content of Merchant resource is not valid. Error: "identifier":"The value does not match the string type"'
            ],
            [
                $correctSample->set(['name' => 1])->toArray(),
                'Content of Merchant resource is not valid. Error: "name":"The value does not match the string type"'
            ],
        ];
    }
}
