<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Merchandises;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Create as CreatePayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\Create as CreateResource;

final class Merchandises extends AbstractAction
{
    public function create(CreatePayload $payload): CreateResource
    {
        $response = $this->getApiClient()->post('/merchandise', $payload);
        return new CreateResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }
}
