<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Alerts;
use Kosv\DonationalertsClient\API\Actions\V1\User\User;

final class V1 extends AbstractAction
{
    public function alerts(): Alerts
    {
        return new Alerts($this->getApiClient());
    }

    public function user(): User
    {
        return new User($this->getApiClient());
    }
}
