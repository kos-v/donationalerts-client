<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use function in_array;
use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\API\Enums\HttpStatusEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\API\ServerException;
use UnexpectedValueException;

final class Client
{
    private const API_URL = 'https://www.donationalerts.com/api';
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    /**
     * @var ApiVersionEnum::*
     * @psalm-readonly
     */
    private string $apiVersion;

    /** @psalm-readonly */
    private Config $config;

    /** @psalm-readonly */
    private Signer $signer;

    /**
     * @var array<HttpStatusEnum::*>
     * @psalm-readonly
     */
    private array $successResponseCodes = [HttpStatusEnum::OK, HttpStatusEnum::CREATED];

    /**
     * @var array<string>
     * @psalm-readonly
     */
    private array $supportedMethods = [self::METHOD_GET, self::METHOD_POST];

    /** @psalm-readonly */
    private TransportClient $transport;

    /**
     * @param ApiVersionEnum::* $apiVersion
     */
    public function __construct(string $apiVersion, Config $config, TransportClient $transport)
    {
        $this->apiVersion = $apiVersion;
        $this->config = $config;
        $this->transport = $transport;
        $this->signer = new Signer($config->getClientSecret());
    }

    public function get(string $endpoint, ?AbstractPayload $payload = null): Response
    {
        if ($payload && !$payload->isFormat(AbstractPayload::FORMAT_GET_PARAMS)) {
            throw new UnexpectedValueException('The payload must contain GET parameters');
        }

        return $this->request(self::METHOD_GET, $endpoint, $payload);
    }

    public function post(string $endpoint, ?AbstractPayload $payload = null): Response
    {
        if ($payload && !$payload->isFormat(AbstractPayload::FORMAT_POST_FIELDS)) {
            throw new UnexpectedValueException('The payload must contain POST fields');
        }

        return $this->request(self::METHOD_POST, $endpoint, $payload);
    }

    private function request(string $method, string $endpoint, ?AbstractPayload $payload): Response
    {
        $this->checkHttpMethod($method);

        $url = $this->buildUrl($endpoint);
        $headers = [
            'Authorization' => 'Bearer ' . $this->config->getAccessToken(),
        ];

        $normalizedPayload = [];
        if ($payload !== null) {
            if ($payload instanceof AbstractSignablePayload) {
                $payload = $this->signer->signPayload($payload);
            }
            $normalizedPayload = $payload->toFormat();
        }

        if ($method === self::METHOD_POST) {
            $response = $this->transport->post($url, $normalizedPayload, $headers);
        } else {
            $response = $this->transport->get($url, $normalizedPayload, $headers);
        }

        $this->checkResponse($response);

        return new Response($response);
    }

    private function buildUrl(string $endpoint): string
    {
        return self::API_URL . "/{$this->apiVersion}" . $endpoint;
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
