<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\CreateCustom as CreateCustomPayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\CreateCustom as CreateCustomResource;

final class Alerts extends AbstractAction
{
    public function createCustom(CreateCustomPayload $payload): CreateCustomResource
    {
        $response = $this->getApiClient()->post('/custom_alert', $payload);
        return new CreateCustomResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }
}
