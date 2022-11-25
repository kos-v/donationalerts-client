<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\Users;

use Kosv\DonationalertsClient\API\Actions\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Resources\Users\ProfileInfo;

final class Users extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getProfileInfo(): ProfileInfo
    {
        return new ProfileInfo($this->client->get('/user/oauth')->toArray());
    }
}
