<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Users;

use Kosv\DonationalertsClient\API\Actions\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Resources\V1\Users\ProfileInfo;

final class Users extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getProfileInfo(): ProfileInfo
    {
        return new ProfileInfo($this->client->get('/v1/user/oauth')->toArray());
    }
}
