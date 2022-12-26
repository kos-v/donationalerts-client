<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Kosv\DonationalertsClient\API\Actions\V1\Donations\Donations;
use Kosv\DonationalertsClient\API\Actions\V1\User\User;
use Kosv\DonationalertsClient\API\Client;

final class V1
{
    /** @psalm-readonly */
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function donations(): Donations
    {
        return new Donations($this->client);
    }

    public function user(): User
    {
        return new User($this->client);
    }
}
