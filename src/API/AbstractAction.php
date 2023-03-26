<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

abstract class AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    final protected function getApiClient(): Client
    {
        return $this->client;
    }
}
