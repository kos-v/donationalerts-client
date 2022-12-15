<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;

final class Config
{
    /** @psalm-readonly */
    private AccessToken $accessToken;

    /**
     * @var CurrencyEnum::*
     * @psalm-readonly
     */
    private string $inCurrency;

    /**
     * @var LangEnum::*
     * @psalm-readonly
     */
    private string $lang;

    /**
     * @var CurrencyEnum::*
     * @psalm-readonly
     */
    private string $outCurrency;

    /**
     * @param LangEnum::* $lang
     * @param CurrencyEnum::* $inCurrency
     * @param CurrencyEnum::* $outCurrency
     */
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

    /**
     * @return CurrencyEnum::*
     */
    public function getInCurrency(): string
    {
        return $this->inCurrency;
    }

    /**
     * @return LangEnum::*
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @return CurrencyEnum::*
     */
    public function getOutCurrency(): string
    {
        return $this->outCurrency;
    }
}
