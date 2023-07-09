<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Contracts;

interface TransportClient
{
    /**
     * @throws TransportClientError
     */
    public function get(string $url, array $payload = [], array $headers = []): TransportResponse;

    /**
     * @throws TransportClientError
     */
    public function post(string $url, array $payload = [], array $headers = []): TransportResponse;

    /**
     * @throws TransportClientError
     */
    public function put(string $url, array $payload = [], array $headers = []): TransportResponse;
}
