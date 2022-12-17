<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Enums;

final class CurrencyEnum
{
    public const BRL = 'BRL';
    public const BYN = 'BYN';
    public const EUR = 'EUR';
    public const KZT = 'KZT';
    public const RUB = 'RUB';
    public const TRY = 'TRY';
    public const UAH = 'UAH';
    public const USD = 'USD';

    public static function getAll(): array
    {
        return [
            self::BRL,
            self::BYN,
            self::EUR,
            self::KZT,
            self::RUB,
            self::TRY,
            self::UAH,
            self::USD,
        ];
    }
}
