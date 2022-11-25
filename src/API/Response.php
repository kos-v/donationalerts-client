<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\Contracts\TransportResponse;

final class Response
{
    private TransportResponse $response;

    public function __construct(TransportResponse $response)
    {
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function toArray(): array
    {
        return $this->response->toArray();
    }
}
