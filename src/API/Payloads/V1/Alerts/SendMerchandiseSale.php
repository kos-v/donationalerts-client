<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractSignablePayload;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\Rules\StringLenRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class SendMerchandiseSale extends AbstractSignablePayload
{
    public const F_USER_ID = 'user_id';
    public const F_EXTERNAL_ID = 'external_id';
    public const F_MERCHANT_IDENTIFIER = 'merchant_identifier';
    public const F_MERCHANDISE_IDENTIFIER = 'merchandise_identifier';
    public const F_AMOUNT = 'amount';
    public const F_CURRENCY = 'currency';
    public const F_BOUGHT_AMOUNT = 'bought_amount';
    public const F_USERNAME = 'username';
    public const F_MESSAGE = 'message';

    /**
     * @param array<self::F_*, mixed> $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);
    }

    protected function getSignatureFieldKey(): string
    {
        return 'signature';
    }

    protected function validateFields(array $fields): ValidationErrors
    {
        return (new Validator([
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [
                self::F_USER_ID, self::F_EXTERNAL_ID, self::F_MERCHANT_IDENTIFIER,
                self::F_MERCHANDISE_IDENTIFIER, self::F_AMOUNT, self::F_CURRENCY
            ]),
            new IsTypeRule(self::F_USER_ID, 'integer'),
            new IsTypeRule(self::F_EXTERNAL_ID, 'string'),
            new IsTypeRule(self::F_MERCHANT_IDENTIFIER, 'string'),
            new IsTypeRule(self::F_MERCHANDISE_IDENTIFIER, 'string'),
            new IsTypeRule(self::F_AMOUNT, 'numeric'),
            new IsTypeRule(self::F_CURRENCY, 'string'),
            new IsTypeRule(self::F_BOUGHT_AMOUNT, 'integer'),
            new IsTypeRule(self::F_USERNAME, 'string'),
            new IsTypeRule(self::F_MESSAGE, 'string'),
            new StringLenRule(self::F_EXTERNAL_ID, 1, 32),
            new InRule(self::F_CURRENCY, [
                CurrencyEnum::EUR,
                CurrencyEnum::USD,
                CurrencyEnum::RUB,
                CurrencyEnum::BRL,
                CurrencyEnum::TRY,
            ]),
        ]))->validate($fields);
    }
}
