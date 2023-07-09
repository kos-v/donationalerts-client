<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use function in_array;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\API\Enums\HttpStatusEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\API\ServerException;
use LogicException;

final class Client
{
    private const API_URL = 'https://www.donationalerts.com/api';
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PUT = 'PUT';

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
        return $this->request(self::METHOD_GET, $endpoint, $payload);
    }

    public function post(string $endpoint, ?AbstractPayload $payload = null): Response
    {
        return $this->request(self::METHOD_POST, $endpoint, $payload);
    }

    public function put(string $endpoint, ?AbstractPayload $payload = null): Response
    {
        return $this->request(self::METHOD_PUT, $endpoint, $payload);
    }

    /**
     * @param self::METHOD_* $method
     */
    private function request(string $method, string $endpoint, ?AbstractPayload $payload): Response
    {
        $url = $this->buildUrl($endpoint);
        $headers = [
            'Authorization' => 'Bearer ' . $this->config->getAccessToken(),
        ];

        $payloadFields = [];
        if ($payload !== null) {
            if ($payload instanceof AbstractSignablePayload) {
                $payload = $this->signer->signPayload($payload);
            }
            $payloadFields = $payload->getFields();
        }

        switch ($method) {
            case self::METHOD_GET:
                $response = $this->transport->get($url, $payloadFields, $headers);
                break;
            case self::METHOD_POST:
                $response = $this->transport->post($url, $payloadFields, $headers);
                break;
            case self::METHOD_PUT:
                $response = $this->transport->put($url, $payloadFields, $headers);
                break;
            default:
                throw new LogicException("{$method} http-method is not supported");
        }

        $this->checkResponse($response);

        return new Response($response);
    }

    private function buildUrl(string $endpoint): string
    {
        return self::API_URL . "/{$this->apiVersion}" . $endpoint;
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
