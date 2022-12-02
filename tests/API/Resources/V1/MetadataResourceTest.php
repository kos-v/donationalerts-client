<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1;

use Kosv\DonationalertsClient\API\Resources\V1\MetadataResource;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class MetadataResourceTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource = new MetadataResource([
            'current_page' => 1,
            'from' => 1,
            'last_page' => 1,
            'path' => 'https://www.donationalerts.com/api/v1/alerts/donations',
            'per_page' => 30,
            'to' => 1,
            'total' => 90,
        ]);

        $this->assertEquals(1, $resource->getCurrentPage());
        $this->assertEquals(30, $resource->getPerPage());
        $this->assertEquals(90, $resource->getTotalCount());
    }

    public function testUnexpectedContentFormat(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of MetadataResource resource is not valid. Error: "[*]":"The value must be keyable array type"');

        new MetadataResource([
            [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'path' => 'https://www.donationalerts.com/api/v1/alerts/donations',
                'per_page' => 30,
                'to' => 1,
                'total' => 90,
            ],
        ]);
    }

    public function testWithoutRequiredFields(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of MetadataResource resource is not valid. Error: "[*]":"Required fields [current_page, per_page, total] are not set"');

        new MetadataResource([
            'from' => 1,
            'last_page' => 1,
            'path' => 'https://www.donationalerts.com/api/v1/alerts/donations',
            'to' => 1,
        ]);
    }
}
