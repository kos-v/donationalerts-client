<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Alerts;

use DateTimeImmutable;
use DateTimeZone;
use Kosv\DonationalertsClient\API\AbstractResource;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\DatetimeFormatRule;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class SendMerchandiseSale extends AbstractResource
{
    public function getAmount(): float
    {
        return (float)$this->getContentValue('amount');
    }

    public function getBoughtAmount(): int
    {
        /** @var int $boughtAmount */
        $boughtAmount = $this->getContentValue('bought_amount');
        return $boughtAmount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        /** @var string $createdAtRaw */
        $createdAtRaw = $this->getContentValue('created_at');

        /** @var DateTimeImmutable $createdAt */
        $createdAt = DateTimeImmutable::createFromFormat(
            'Y-m-d H.i.s',
            $createdAtRaw,
            new DateTimeZone('UTC')
        );

        return $createdAt;
    }

    public function getCurrency(): string
    {
        /** @var string $currency */
        $currency = $this->getContentValue('currency');
        return $currency;
    }

    public function getExternalId(): string
    {
        /** @var string $externalId */
        $externalId = $this->getContentValue('external_id');
        return $externalId;
    }

    public function getId(): int
    {
        /** @var int $id */
        $id = $this->getContentValue('id');
        return $id;
    }

    public function getIsShown(): int
    {
        /** @var int $isShown */
        $isShown = $this->getContentValue('is_shown');
        return $isShown;
    }

    public function getMessage(): ?string
    {
        /** @var string|null $message */
        $message = $this->getContentValue('message');
        return $message;
    }

    public function getName(): string
    {
        /** @var string $name */
        $name = $this->getContentValue('name');
        return $name;
    }

    public function getShownAt(): ?DateTimeImmutable
    {
        /** @var string $shownAtRaw */
        $shownAtRaw = $this->getContentValue('shown_at');

        /** @var DateTimeImmutable $shownAt */
        $shownAt = $shownAtRaw
            ? DateTimeImmutable::createFromFormat(
                'Y-m-d H.i.s',
                $shownAtRaw,
                new DateTimeZone('UTC')
            )
            : null;

        return $shownAt;
    }

    public function getUsername(): ?string
    {
        /** @var string|null $username */
        $username = $this->getContentValue('username');
        return $username;
    }


    protected function validateContent(array $content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                [
                    'id', 'name', 'external_id',
                    'username', 'message', 'amount',
                    'currency', 'bought_amount', 'is_shown',
                    'created_at', 'shown_at'
                ],
            ),
            new IsTypeRule('id', 'integer'),
            new IsTypeRule('name', 'string'),
            new IsTypeRule('external_id', 'string'),
            new IsTypeRule('username', 'string', true),
            new IsTypeRule('message', 'string', true),
            new IsTypeRule('amount', 'numeric'),
            new IsTypeRule('currency', 'string'),
            new IsTypeRule('bought_amount', 'integer'),
            new IsTypeRule('is_shown', 'integer'),
            new IsTypeRule('created_at', 'string'),
            new IsTypeRule('shown_at', 'string', true),
            new InRule('is_shown', [0, 1]),
            new InRule('currency', CurrencyEnum::getAll()),
            new DatetimeFormatRule('created_at', 'Y-m-d H.i.s'),
            new DatetimeFormatRule('shown_at', 'Y-m-d H.i.s', true),
        ]))->validate($content);
    }
}
