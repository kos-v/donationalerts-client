<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Actions\V1\V1;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Transport\CurlClient;

final class Api
{
    private Client $client;

    public function __construct(Config $config, ?TransportClient $transport = null)
    {
        $this->client = new Client($config, $transport ?? new CurlClient());
    }

    public function v1(): V1
    {
        return new V1($this->client);
    }
}
