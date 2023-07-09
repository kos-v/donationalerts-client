<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads\V1\Merchandises;

use Kosv\DonationalertsClient\API\AbstractSignablePayload;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\AllowedArrayKeysRule;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\Rules\StringLenRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class Create extends AbstractSignablePayload
{
    public const F_MERCHANT_IDENTIFIER = 'merchant_identifier';
    public const F_MERCHANDISE_IDENTIFIER = 'merchandise_identifier';
    public const F_TITLE = 'title';
    public const F_IS_ACTIVE = 'is_active';
    public const F_IS_PERCENTAGE = 'is_percentage';
    public const F_CURRENCY = 'currency';
    public const F_PRICE_USER = 'price_user';
    public const F_PRICE_SERVICE = 'price_service';
    public const F_URL = 'url';
    public const F_IMG_URL = 'img_url';
    public const F_END_AT_TS = 'end_at_ts';

    protected function getSignatureFieldKey(): string
    {
        return 'signature';
    }

    protected function validateFields(array $fields): ValidationErrors
    {
        return (new Validator([
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [
                self::F_MERCHANT_IDENTIFIER,
                self::F_MERCHANDISE_IDENTIFIER,
                self::F_TITLE,
                self::F_CURRENCY,
                self::F_PRICE_USER,
                self::F_PRICE_SERVICE,
            ]),
            new IsTypeRule(self::F_MERCHANT_IDENTIFIER, 'string'),
            new IsTypeRule(self::F_MERCHANDISE_IDENTIFIER, 'string'),
            new IsTypeRule(self::F_TITLE, 'array'),
            new IsTypeRule(self::F_IS_ACTIVE, 'integer'),
            new IsTypeRule(self::F_IS_PERCENTAGE, 'integer'),
            new IsTypeRule(self::F_CURRENCY, 'string'),
            new IsTypeRule(self::F_PRICE_USER, 'numeric'),
            new IsTypeRule(self::F_PRICE_SERVICE, 'numeric'),
            new IsTypeRule(self::F_URL, 'string'),
            new IsTypeRule(self::F_IMG_URL, 'string'),
            new IsTypeRule(self::F_END_AT_TS, 'integer'),
            new StringLenRule(self::F_MERCHANDISE_IDENTIFIER, 1, 16),
            new RequiredFieldRule(self::F_TITLE, [LangEnum::ENGLISH_USA]),
            new AllowedArrayKeysRule(self::F_TITLE, LangEnum::getAll()),
            new IsTypeRule(self::F_TITLE . '.' . KeysEnum::ALL_IN_LIST, 'string'),
            new StringLenRule(self::F_TITLE . '.' . KeysEnum::ALL_IN_LIST, 1, 1024),
            new InRule(self::F_IS_ACTIVE, [0, 1]),
            new InRule(self::F_IS_PERCENTAGE, [0, 1]),
            new InRule(self::F_CURRENCY, [
                CurrencyEnum::EUR,
                CurrencyEnum::USD,
                CurrencyEnum::RUB,
                CurrencyEnum::BRL,
                CurrencyEnum::TRY,
            ]),
            new StringLenRule(self::F_URL, 0, 128),
            new StringLenRule(self::F_IMG_URL, 0, 128),
        ]))->validate($fields);
    }
}
