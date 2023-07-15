<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\SendCustom as SendCustomPayload;
use Kosv\DonationalertsClient\API\Payloads\V1\Alerts\SendMerchandiseSale as SendMerchandiseSalePayload;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\SendCustom as SendCustomResource;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\SendMerchandiseSale as SendMerchandiseSaleResource;

final class Alerts extends AbstractAction
{
    public function sendCustom(SendCustomPayload $payload): SendCustomResource
    {
        $response = $this->getApiClient()->post('/custom_alert', $payload);
        return new SendCustomResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }

    public function sendMerchandiseSale(SendMerchandiseSalePayload $payload): SendMerchandiseSaleResource
    {
        $response = $this->getApiClient()->post('/merchandise_sale', $payload);
        return new SendMerchandiseSaleResource(
            (new RawResourceExtractor($response->toArray()))->extractContent()
        );
    }
}
