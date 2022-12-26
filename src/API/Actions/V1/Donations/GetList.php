<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Donations;

use Kosv\DonationalertsClient\API\Actions\V1\AbstractGetList;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Resources\V1\Donations\GetListItem;

/**
 * @method GetListItem[] getAll()
 * @method GetListItem[] getAllOfPage()
 */
final class GetList extends AbstractGetList
{
    protected function makeIterator(Client $client, int $page, bool $onlyCurrentPage): GetListIterator
    {
        return new GetListIterator($client, $page, $onlyCurrentPage);
    }
}
