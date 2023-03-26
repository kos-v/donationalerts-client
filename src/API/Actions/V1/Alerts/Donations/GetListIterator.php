<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations;

use Kosv\DonationalertsClient\API\Actions\V1\AbstractGetListIterator;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\Donations\GetList as GetListPayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\AbstractCollection;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\Donations\GetListCollection;
use Kosv\DonationalertsClient\API\Resources\V1\Metadata;
use Kosv\DonationalertsClient\API\Response;

final class GetListIterator extends AbstractGetListIterator
{
    protected function extractItems(RawResourceExtractor $extractor): AbstractCollection
    {
        return new GetListCollection($extractor->extractContent('data', false));
    }

    protected function extractMetadata(RawResourceExtractor $extractor): Metadata
    {
        return new Metadata($extractor->extractMetadata());
    }

    protected function requestItems(Client $client, int $page): Response
    {
        return $client->get('/alerts/donations', new GetListPayload([
            GetListPayload::P_PAGE => $page
        ]));
    }
}
