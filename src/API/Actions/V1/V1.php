<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Kosv\DonationalertsClient\API\Actions\V1\Users\Users;
use Kosv\DonationalertsClient\API\Client;

final class V1
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function users(): Users
    {
        return new Users($this->client);
    }
}
