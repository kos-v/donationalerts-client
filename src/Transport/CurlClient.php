<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Transport;

use Curl\Curl;
use InvalidArgumentException;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportClientError;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Transport\Exceptions\TransportClientException;

final class CurlClient implements TransportClient
{
    private const DEFAULT_CONNECTION_TIMEOUT = 30;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private int $connectionTimeout;

    public function __construct(int $connectionTimeout = self::DEFAULT_CONNECTION_TIMEOUT)
    {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * @throws TransportClientError
     */
    public function get(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        return $this->request(self::METHOD_GET, $url, $payload, $headers);
    }

    /**
     * @throws TransportClientError
     */
    public function post(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        return $this->request(self::METHOD_POST, $url, $payload, $headers);
    }

    private function makeCurlObject(array $headers = []): Curl
    {
        $curl = new Curl();
        $curl->error(static function () use ($curl) {
            if ($curl->isCurlError()) {
                throw new TransportClientException(
                    (string)$curl->getCurlErrorMessage(),
                    $curl->getCurlErrorCode()
                );
            }
        });
        $curl->setConnectTimeout($this->connectionTimeout);
        $curl->setHeaders($headers);
        return $curl;
    }

    private function request(string $method, string $url, array $payload = [], array $headers = []): TransportResponse
    {
        if (!in_array($method, [self::METHOD_GET, self::METHOD_POST], true)) {
            throw new InvalidArgumentException("Method {$method} is not supported");
        }

        $curl = $this->makeCurlObject($headers);
        if ($method === self::METHOD_POST) {
            $curl->post($url, $payload);
        } else {
            $curl->get($url, $payload);
        }

        return new Response((string)$curl->getRawResponse(), $curl->getHttpStatusCode());
    }
}
