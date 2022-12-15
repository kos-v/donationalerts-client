<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Enums\HttpStatusEnum;
use function in_array;
use InvalidArgumentException;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\API\ServerException;

final class Client
{
    private const API_URL = 'https://www.donationalerts.com/api';
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private Config $config;

    /** @var array<HttpStatusEnum::*> */
    private array $successResponseCodes = [HttpStatusEnum::OK, HttpStatusEnum::CREATED];

    /** @var array<string> */
    private array $supportedMethods = [self::METHOD_GET, self::METHOD_POST];

    private TransportClient $transport;

    public function __construct(Config $config, TransportClient $transport)
    {
        $this->config = $config;
        $this->transport = $transport;
    }

    public function get(string $endpoint, array $payload = []): Response
    {
        return $this->request(self::METHOD_GET, $endpoint, $payload);
    }

    public function post(string $endpoint, array $payload = []): Response
    {
        return $this->request(self::METHOD_POST, $endpoint, $payload);
    }

    private function request(string $method, string $endpoint, array $payload = []): Response
    {
        $this->checkHttpMethod($method);

        $url = $this->buildUrl($endpoint);
        $headers = [
            'Authorization' => 'Bearer ' . $this->config->getAccessToken(),
        ];

        if ($method === self::METHOD_POST) {
            $response = $this->transport->post($url, $payload, $headers);
        } else {
            $response = $this->transport->get($url, $payload, $headers);
        }

        $this->checkResponse($response);

        return new Response($response);
    }

    private function buildUrl(string $endpoint): string
    {
        return self::API_URL . $endpoint;
    }

    private function checkHttpMethod(string $method): void
    {
        if (!in_array($method, $this->supportedMethods, true)) {
            throw new InvalidArgumentException("{$method} http-method is not supported");
        }
    }

    private function checkResponse(TransportResponse $response): void
    {
        if (!in_array($response->getStatusCode(), $this->successResponseCodes, true)) {
            throw new ServerException(
                'The server returned unexpected http-code',
                $response->getStatusCode(),
                (string)$response
            );
        }

        if (!$response->isJson()) {
            throw new ServerException(
                'The server returned response in not json format',
                $response->getStatusCode(),
                (string)$response
            );
        }
    }
}
