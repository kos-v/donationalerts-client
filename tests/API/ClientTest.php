<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API;

use Kosv\DonationalertsClient\API\AbstractPayload;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Config;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\API\ServerException;
use Kosv\DonationalertsClient\Transport\Response;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use LogicException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class ClientTest extends TestCase
{
    public function testRequestGet(): void
    {
        $client = new Client(
            ApiVersionEnum::V1,
            $this->makeClientConfig(),
            new class ($this) implements TransportClient {
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
            }
        );

        $response = $client->get(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100], AbstractPayload::FORMAT_GET_PARAMS)
        );
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestGetWithUnsupportedPayloadFormat(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('The payload must contain GET parameters');

        $transport = $this->createMock(TransportClient::class);
        $client = new Client(ApiVersionEnum::V1, $this->makeClientConfig(), $transport);
        $client->get(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100], 'not correct')
        );
    }

    public function testRequestPost(): void
    {
        $client = new Client(
            ApiVersionEnum::V1,
            $this->makeClientConfig(),
            new class ($this) implements TransportClient {
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
            }
        );

        $response = $client->post(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100], AbstractPayload::FORMAT_POST_FIELDS)
        );
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestPostWithUnsupportedPayloadFormat(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('The payload must contain POST fields');

        $transport = $this->createMock(TransportClient::class);
        $client = new Client(ApiVersionEnum::V1, $this->makeClientConfig(), $transport);
        $client->post(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100], 'not correct')
        );
    }

    public function testRequestWhenResponseNot200xHttpCode(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned unexpected http-code');

        $transport = $this->createMock(TransportClient::class);
        $transport->method('get')->willReturn(new Response('{"error": "server error"}', 500));

        $client = new Client(ApiVersionEnum::V1, $this->makeClientConfig(), $transport);
        $client->get('/test/foo');
    }

    public function testRequestWhenResponseNotJsonFormat(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The server returned response in not json format');

        $transport = $this->createMock(TransportClient::class);
        $transport->method('get')->willReturn(new Response('<h1>Hi!</h1>', 200));

        $client = new Client(ApiVersionEnum::V1, $this->makeClientConfig(), $transport);
        $client->get('/test/foo');
    }

    private function makeClientConfig(): Config
    {
        return new Config(new AccessToken('secret', 1668719637, 'Bearer', 'secret_new'));
    }

    private function makePayloadStub(array $fields, string $format): AbstractPayload
    {
        return new class ($fields, $format) extends AbstractPayload {
            protected function validatePayload($payload): ValidationErrors
            {
                return new ValidationErrors();
            }
        };
    }
}
