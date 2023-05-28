<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Alerts;

use Kosv\DonationalertsClient\API\Resources\V1\Alerts\CreateCustom;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class CreateCustomTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource1 = new CreateCustom([
            'id' => 24,
            'external_id' => '12',
            'header' => 'Custom header',
            'message' => 'Custom message',
            'image_url' => 'http://example.local/image.png',
            'sound_url' => 'http://example.local/audio.ogg',
            'is_shown' => 0,
            "created_at" => "2020-09-24 12:04:23",
            "shown_at" => "2020-09-25 00:05:59",
        ]);

        $this->assertEquals(24, $resource1->getId());
        $this->assertEquals('12', $resource1->getExternalId());
        $this->assertEquals('Custom header', $resource1->getHeader());
        $this->assertEquals('Custom message', $resource1->getMessage());
        $this->assertEquals('http://example.local/image.png', $resource1->getImageUrl());
        $this->assertEquals('http://example.local/audio.ogg', $resource1->getSoundUrl());
        $this->assertEquals(0, $resource1->getIsShown());
        $this->assertEquals('2020-09-24 12:04:23', $resource1->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals('2020-09-25 00:05:59', $resource1->getShownAt()->format('Y-m-d H:i:s'));

        $resource2 = new CreateCustom([
            'id' => 24,
            'external_id' => null,
            'header' => null,
            'message' => null,
            'image_url' => null,
            'sound_url' => null,
            'is_shown' => 0,
            "created_at" => "2020-09-24 12:04:23",
            "shown_at" => null,
        ]);

        $this->assertNull($resource2->getExternalId());
        $this->assertNull($resource2->getHeader());
        $this->assertNull($resource2->getMessage());
        $this->assertNull($resource2->getImageUrl());
        $this->assertNull($resource2->getSoundUrl());
        $this->assertNull($resource2->getShownAt());
    }

    public function testUnexpectedContentFormat(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of CreateCustom resource is not valid. Error: "[*]":"The value must be keyable array type"');

        new CreateCustom([[]]);
    }

    public function testWithoutRequiredFields(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of CreateCustom resource is not valid. Error: "[*]":"Required fields [id, external_id, header, message, image_url, sound_url, is_shown, created_at, shown_at] are not set"');

        new CreateCustom([]);
    }

    public function testWithUnexpectedFieldType(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of CreateCustom resource is not valid. Error: "id":"The value does not match the integer type"');

        new CreateCustom([
            'id' => 'id',
            'external_id' => '12',
            'header' => 'Custom header',
            'message' => 'Custom message',
            'image_url' => 'http://example.local/image.png',
            'sound_url' => 'http://example.local/audio.ogg',
            'is_shown' => 0,
            "created_at" => "2020-09-24 12:04:23",
            "shown_at" => "2020-09-25 00:05:59",
        ]);
    }
}
