<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Actions\Users\Users;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Transport\CurlClient;

final class Api
{
    private Client $client;

    public function __construct(Config $config, ?TransportClient $transport = null)
    {
        $this->client = new Client($config, $transport ?? new CurlClient());
    }

    public function users(): Users
    {
        return new Users($this->client);
    }
}
