<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations\Donations;

final class Alerts extends AbstractAction
{
    public function donations(): Donations
    {
        return new Donations($this->getApiClient());
    }
}
