<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Donations;

use Kosv\DonationalertsClient\API\AbstractResource;
use Kosv\DonationalertsClient\API\Resources\V1\AbstractCollection;

final class GetListCollection extends AbstractCollection
{
    protected function makeItemResource($content): AbstractResource
    {
        return new GetListItem($content);
    }
}
