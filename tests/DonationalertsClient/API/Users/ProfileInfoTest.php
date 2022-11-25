<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\Users;

use Kosv\DonationalertsClient\API\Resources\Users\ProfileInfo;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class ProfileInfoTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource = new ProfileInfo([
            'data' => [
                'id' => 3,
                'code' => 'tris_the_jam_master',
                'name' => 'Tris_the_Jam_Master',
                'avatar' => 'https://test.local/foo/300x300.jpeg',
                'email' => 'test@host.local',
                'socket_connection_token' => 'yeJ0eXTY...'
            ]
        ]);

        $this->assertEquals(3, $resource->getId());
        $this->assertEquals('tris_the_jam_master', $resource->getCode());
        $this->assertEquals('Tris_the_Jam_Master', $resource->getName());
        $this->assertEquals('https://test.local/foo/300x300.jpeg', $resource->getAvatar());
        $this->assertEquals('test@host.local', $resource->getEmail());
        $this->assertEquals('yeJ0eXTY...', $resource->getSocketConnectionToken());
    }

    public function testUnexpectedPayloadFormat(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Payload of ProfileInfo resource is not valid. Error: payload not contains required content key "data"');

        new ProfileInfo([
            'foo' => [
                'id' => 3,
                'code' => 'tris_the_jam_master',
                'name' => 'Tris_the_Jam_Master',
                'avatar' => 'https://test.local/foo/300x300.jpeg',
                'email' => 'test@host.local',
                'socket_connection_token' => 'yeJ0eXTY...'
            ]
        ]);
    }

    public function testWithoutRequiredFields(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Payload of ProfileInfo resource is not valid. Error: "[*]":"Required fields id, name, email are not set"');

        new ProfileInfo([
            'data' => [
                'code' => 'tris_the_jam_master',
                'avatar' => 'https://test.local/foo/300x300.jpeg',
                'socket_connection_token' => 'yeJ0eXTY...'
            ]
        ]);
    }

    public function testWithUnexpectedFieldType(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Payload of ProfileInfo resource is not valid. Error: "id":"The value does not match the integer type"');

        new ProfileInfo([
            'data' => [
                'id' => '3',
                'code' => 'tris_the_jam_master',
                'name' => 'Tris_the_Jam_Master',
                'avatar' => 'https://test.local/foo/300x300.jpeg',
                'email' => 'test@host.local',
                'socket_connection_token' => 'yeJ0eXTY...'
            ]
        ]);
    }
}
