<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Alerts;
use Kosv\DonationalertsClient\API\Actions\V1\Donations\Donations;
use Kosv\DonationalertsClient\API\Actions\V1\Merchandises\Merchandises;
use Kosv\DonationalertsClient\API\Actions\V1\Users\Users;

final class V1 extends AbstractAction
{
    public function alerts(): Alerts
    {
        return new Alerts($this->getApiClient());
    }

    public function donations(): Donations
    {
        return new Donations($this->getApiClient());
    }

    public function merchandises(): Merchandises
    {
        return new Merchandises($this->getApiClient());
    }

    public function users(): Users
    {
        return new Users($this->getApiClient());
    }
}
