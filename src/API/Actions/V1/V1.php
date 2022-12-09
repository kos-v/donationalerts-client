<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Kosv\DonationalertsClient\API\Actions\V1\User\User;
use Kosv\DonationalertsClient\API\Client;

final class V1
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function user(): User
    {
        return new User($this->client);
    }
}
