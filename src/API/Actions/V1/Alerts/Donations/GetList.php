<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations;

use Kosv\DonationalertsClient\API\Actions\V1\AbstractGetList;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\Donations\GetList as GetListPayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\Donations\GetListItem;
use Kosv\DonationalertsClient\API\Resources\V1\Metadata;

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

    protected function makeMetadata(Client $client, int $page): Metadata
    {
        $payload = new GetListPayload([
            GetListPayload::P_PAGE => $page,
        ]);

        return new Metadata((new RawResourceExtractor(
            $client->get('/alerts/donations', $payload)->toArray()
        ))->extractMetadata());
    }
}
