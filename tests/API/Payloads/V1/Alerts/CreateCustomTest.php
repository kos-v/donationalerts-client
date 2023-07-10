<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Payloads\V1\Alerts;

use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\CreateCustom;
use PHPUnit\Framework\TestCase;

final class CreateCustomTest extends TestCase
{
    public function testToFormat(): void
    {
        $payloadRawData = [
            CreateCustom::F_EXTERNAL_ID => '12',
            CreateCustom::F_HEADER => 'User',
            CreateCustom::F_MESSAGE => 'Test message',
            CreateCustom::F_IS_SHOWN => 0,
            CreateCustom::F_IMAGE_URL => 'http://example.local/image.png',
            CreateCustom::F_SOUND_URL => 'http://example.local/audio.ogg',
        ];

        $this->assertEquals($payloadRawData, (new CreateCustom($payloadRawData))->getFields());
    }

    public function testUnexpectedContentFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The field external_id is not valid. The value does not match the string type');

        new CreateCustom([
            CreateCustom::F_EXTERNAL_ID => 12,
        ]);
    }
}
