<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API;

use Kosv\DonationalertsClient\API\AbstractPayload;
use Kosv\DonationalertsClient\API\AbstractSignablePayload;
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

final class ClientTest extends TestCase
{
    private const PAYLOAD_SIGNATURE_FIELD_KEY = 'signature';

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

                public function put(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    throw new LogicException('This method should not have been called');
                }
            }
        );

        $response = $client->get(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100])
        );
        $this->assertEquals(['result' => true], $response->toArray());
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

                public function put(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    throw new LogicException('This method should not have been called');
                }
            }
        );

        $response = $client->post(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100])
        );
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestPut(): void
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
                    throw new LogicException('This method should not have been called');
                }

                public function put(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    $this->testCase->assertEquals('https://www.donationalerts.com/api/v1/test/foo', $url);
                    $this->testCase->assertEquals(['bar' => 'val1', 'baz' => 100], $payload);
                    $this->testCase->assertEquals(['Authorization' => 'Bearer secret'], $headers);

                    return new Response('{"result": true}', 200);
                }
            }
        );

        $response = $client->put(
            '/test/foo',
            $this->makePayloadStub(['bar' => 'val1', 'baz' => 100])
        );
        $this->assertEquals(['result' => true], $response->toArray());
    }

    public function testRequestWithSignablePayload(): void
    {
        $client = new Client(
            ApiVersionEnum::V1,
            $this->makeClientConfig(),
            new class ($this, self::PAYLOAD_SIGNATURE_FIELD_KEY) implements TransportClient {
                private string $signatureFieldKey;
                private $testCase;

                public function __construct($testCase, string $signatureFieldKey)
                {
                    $this->testCase = $testCase;
                    $this->signatureFieldKey = $signatureFieldKey;
                }

                public function get(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    $this->testCase->assertEquals(
                        '442c7997ef902c70c9d396d4e70db012a38d5ff7ca480c5588323c17974ac602',
                        $payload[$this->signatureFieldKey]
                    );

                    return new Response('{"result": true}', 200);
                }

                public function post(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    $this->testCase->assertEquals(
                        '0f786a4febb2a9eea9f73156f7de17863724dc815ad96da5afcbb87705ca500e',
                        $payload[$this->signatureFieldKey]
                    );

                    return new Response('{"result": true}', 200);
                }

                public function put(string $url, array $payload = [], array $headers = []): TransportResponse
                {
                    $this->testCase->assertEquals(
                        '31666d58cae7b60c1448953170ad969519408c4cb0bd5eeca27a32403c18b680',
                        $payload[$this->signatureFieldKey]
                    );

                    return new Response('{"result": true}', 200);
                }
            }
        );

        $client->get(
            '/test/foo',
            $this->makeSignablePayload(['foo' => 'get val'])
        );

        $client->post(
            '/test/foo',
            $this->makeSignablePayload(['foo' => 'post val'])
        );

        $client->put(
            '/test/foo',
            $this->makeSignablePayload(['foo' => 'put val'])
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
        return new Config(
            'secret',
            new AccessToken('secret', 1668719637, 'Bearer', 'secret_new')
        );
    }

    private function makePayloadStub(array $fields): AbstractPayload
    {
        return new class ($fields) extends AbstractPayload {
            protected function validateFields(array $fields): ValidationErrors
            {
                return new ValidationErrors();
            }
        };
    }

    private function makeSignablePayload(array $fields): AbstractSignablePayload
    {
        return new class ($fields, self::PAYLOAD_SIGNATURE_FIELD_KEY) extends AbstractSignablePayload {
            private string $signatureFieldKey;

            public function __construct(array $payload, string $signatureFieldKey)
            {
                $this->signatureFieldKey = $signatureFieldKey;
                parent::__construct($payload);
            }

            public function getSignatureFieldKey(): string
            {
                return $this->signatureFieldKey;
            }

            protected function validateFields(array $fields): ValidationErrors
            {
                return new ValidationErrors();
            }
        };
    }
}
