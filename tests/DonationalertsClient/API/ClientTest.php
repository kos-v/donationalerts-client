<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API;

use InvalidArgumentException;
use LogicException;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Config;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\API\ServerException;
use Kosv\DonationalertsClient\Transport\Response;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testRequestGet(): void
    {
        $client = new Client($this->makeClientConfig(), new class ($this) implements TransportClient {
            private $testCase;

            public function __construct($testCase)
            {
                $this->testCase = $testCase;
            }

            public function get(string $url, array $payload = [], array $headers = []): TransportResponse
            {
                $this->testCase->assertEquals('https://www.donationalerts.com/api/v1/test/foo', $url);
                $this->testCase->assertEquals(['bar' => 'val1', 'baz' => 100], $payload);
                $this->testCase->assertEquals(['Authorization' => 'Bearer secret'], $headers);

                return new Response('{"result": true}', 200);
            }

            public function post(string $url, array $payload = [], array $headers = []): TransportResponse
            {
                throw new LogicException('This method should not have been called');
            }
        });

        $response =  $client->get('/test/foo', ['bar' => 'val1', 'baz' => 100]);
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestPost(): void
    {
        $client = new Client($this->makeClientConfig(), new class ($this) implements TransportClient {
            private $testCase;

            public function __construct($testCase)
            {
                $this->testCase = $testCase;
            }

            public function get(string $url, array $payload = [], array $headers = []): TransportResponse
            {
                throw new LogicException('This method should not have been called');
            }

            public function post(string $url, array $payload = [], array $headers = []): TransportResponse
            {
                $this->testCase->assertEquals('https://www.donationalerts.com/api/v1/test/foo', $url);
                $this->testCase->assertEquals(['bar' => 'val1', 'baz' => 100], $payload);
                $this->testCase->assertEquals(['Authorization' => 'Bearer secret'], $headers);

                return new Response('{"result": true}', 200);
            }
        });

        $response =  $client->post('/test/foo', ['bar' => 'val1', 'baz' => 100]);
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestWhenIncorrectApiVersion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API version incorrect_version is not supported');

        $transport = $this->createMock(TransportClient::class);
        $transport->method('get')->willReturn(new Response('{"result": true}', 200));

        $client = new Client($this->makeClientConfig(), $transport);
        $client->get('/test/foo', [], 'incorrect_version');
    }

    public function testRequestWhenResponseNot200xHttpCode(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned unexpected http-code');

        $transport = $this->createMock(TransportClient::class);
        $transport->method('get')->willReturn(new Response('{"error": "server error"}', 500));

        $client = new Client($this->makeClientConfig(), $transport);
        $client->get('/test/foo');
    }

    public function testRequestWhenResponseNotJsonFormat(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned response in not json format');

        $transport = $this->createMock(TransportClient::class);
        $transport->method('get')->willReturn(new Response('<h1>Hi!</h1>', 200));

        $client = new Client($this->makeClientConfig(), $transport);
        $client->get('/test/foo');
    }

    private function makeClientConfig(): Config
    {
        return new Config(new AccessToken('secret', 1668719637, 'Bearer', 'secret_new'));
    }
}
