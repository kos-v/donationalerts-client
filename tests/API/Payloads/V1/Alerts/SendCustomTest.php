<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Payloads\V1\Alerts;

use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\SendCustom;
use PHPUnit\Framework\TestCase;

final class SendCustomTest extends TestCase
{
    public function testToFormat(): void
    {
        $payloadRawData = [
            SendCustom::F_EXTERNAL_ID => '12',
            SendCustom::F_HEADER => 'User',
            SendCustom::F_MESSAGE => 'Test message',
            SendCustom::F_IS_SHOWN => 0,
            SendCustom::F_IMAGE_URL => 'http://example.local/image.png',
            SendCustom::F_SOUND_URL => 'http://example.local/audio.ogg',
        ];

        $this->assertEquals($payloadRawData, (new SendCustom($payloadRawData))->getFields());
    }

    public function testUnexpectedContentFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The field external_id is not valid. The value does not match the string type');

        new SendCustom([
            SendCustom::F_EXTERNAL_ID => 12,
        ]);
    }
}
