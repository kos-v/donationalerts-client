<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Transport;

use Kosv\DonationalertsClient\Transport\Response;
use PHPUnit\Framework\TestCase;
use JsonException;

final class ResponseTest extends TestCase
{
    public function test__toString(): void
    {
        $this->assertEquals((string)new Response('', 200), '');
        $this->assertEquals((string)new Response('{"foo": "bar"}', 200), '{"foo": "bar"}');
        $this->assertEquals((string)new Response('<b>foo</b>', 200), '<b>foo</b>');
    }

    public function testGetStatusCode(): void
    {
        $this->assertEquals((new Response('', 200))->getStatusCode(), 200);
        $this->assertEquals((new Response('', 404))->getStatusCode(), 404);
    }

    public function testIsJson(): void
    {
        $this->assertFalse((new Response('', 200))->isJson());
        $this->assertTrue((new Response('{}', 200))->isJson());
        $this->assertTrue((new Response('[]', 200))->isJson());
        $this->assertFalse((new Response('<b>foo</b>', 200))->isJson());
        $this->assertTrue((new Response('{"foo": "bar"}', 200))->isJson());
    }

    public function testToArrayWhenResponseContainsJson(): void
    {
        $this->assertEquals((new Response('{}', 200))->toArray(), []);
        $this->assertEquals((new Response('[]', 200))->toArray(), []);
        $this->assertEquals((new Response('{"foo": {"bar": "baz"}}', 200))->toArray(), [
            "foo" => ["bar" => "baz"]
        ]);
        $this->assertEquals((new Response('[{"foo": {"bar": "baz"}}]', 200))->toArray(), [
            ["foo" => ["bar" => "baz"]]
        ]);
    }

    public function testToArrayWhenResponseContainsEmptyBody(): void
    {
        $this->expectException(JsonException::class);
        (new Response('', 200))->toArray();
    }

    public function testToArrayWhenResponseContainsNotJson(): void
    {
        $this->expectException(JsonException::class);
        (new Response('<b>foo</b>', 200))->toArray();
    }
}
