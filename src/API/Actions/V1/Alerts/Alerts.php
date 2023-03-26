<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations\Donations;
use Kosv\DonationalertsClient\API\Client;

final class Alerts extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function donations(): Donations
    {
        return new Donations($this->client);
    }
}
