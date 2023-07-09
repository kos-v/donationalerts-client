<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Merchandises;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Create as CreatePayload;
use Kosv\DonationalertsClient\API\Payloads\V1\Merchandises\Update as UpdatePayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\CreateUpdate as CreateUpdateResource;

final class Merchandises extends AbstractAction
{
    public function create(CreatePayload $payload): CreateUpdateResource
    {
        $response = $this->getApiClient()->post('/merchandise', $payload);
        return new CreateUpdateResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }

    public function update(int $mid, UpdatePayload $payload): CreateUpdateResource
    {
        $response = $this->getApiClient()->put("/merchandise/{$mid}", $payload);
        return new CreateUpdateResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }
}
