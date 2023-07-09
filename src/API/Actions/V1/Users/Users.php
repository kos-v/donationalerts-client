<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Users;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Users\ProfileInfo;

final class Users extends AbstractAction
{
    public function getProfileInfo(): ProfileInfo
    {
        return new ProfileInfo(
            (new RawResourceExtractor($this->getApiClient()->get('/user/oauth')->toArray()))
                ->extractContent()
        );
    }
}
