<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Transport;

use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportClientError;
use Kosv\DonationalertsClient\Contracts\TransportResponse;

final class LimitableRequestsClient implements TransportClient
{
    private const DEFAULT_REQUESTS_LIMIT_TIMEOUT = 1000;

    /** @psalm-readonly */
    private TransportClient $client;

    /** @psalm-readonly */
    private RequestsLimiter $requestsLimiter;

    public function __construct(
        int $requestsLimitTimeout = self::DEFAULT_REQUESTS_LIMIT_TIMEOUT,
        ?TransportClient $client = null
    ) {
        $this->requestsLimiter = new RequestsLimiter($requestsLimitTimeout);
        $this->client = $client ?? new CurlClient();
    }

    /**
     * @throws TransportClientError
     */
    public function get(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        $this->requestsLimiter->wait();
        return $this->client->get($url, $payload, $headers);
    }

    /**
     * @throws TransportClientError
     */
    public function post(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        $this->requestsLimiter->wait();
        return $this->client->post($url, $payload, $headers);
    }

    /**
     * @throws TransportClientError
     */
    public function put(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        $this->requestsLimiter->wait();
        return $this->client->put($url, $payload, $headers);
    }
}
