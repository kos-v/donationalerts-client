<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Actions\V1\V1;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Transport\CurlClient;

final class Api
{
    /** @psalm-readonly */
    private Config $config;

    /** @psalm-readonly */
    private TransportClient $transport;

    public function __construct(Config $config, ?TransportClient $transport = null)
    {
        $this->config = $config;
        $this->transport = $transport ?? new CurlClient();
    }

    public function v1(): V1
    {
        return new V1(new Client(ApiVersionEnum::V1, $this->config, $this->transport));
    }
}
