<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\User;

use Kosv\DonationalertsClient\API\Actions\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\User\ProfileInfo;

final class User extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getProfileInfo(): ProfileInfo
    {
        return new ProfileInfo(
            (new RawResourceExtractor($this->client->get('/user/oauth')->toArray()))->extractContent()
        );
    }
}
