<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;

final class Config
{
    private AccessToken $accessToken;
    private string $inCurrency;
    private string $lang;
    private string $outCurrency;

    public function __construct(
        AccessToken $accessToken,
        string $lang = LangEnum::ENGLISH_USA,
        string $inCurrency = CurrencyEnum::USD,
        string $outCurrency = CurrencyEnum::USD
    ) {
        $this->accessToken = $accessToken;
        $this->lang = $lang;
        $this->inCurrency = $inCurrency;
        $this->outCurrency = $outCurrency;
    }

    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    public function getInCurrency(): string
    {
        return $this->inCurrency;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getOutCurrency(): string
    {
        return $this->outCurrency;
    }
}
