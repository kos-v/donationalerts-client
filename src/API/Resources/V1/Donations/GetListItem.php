<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Donations;

use DateTimeImmutable;
use DateTimeZone;
use Kosv\DonationalertsClient\API\Enums\CurrencyEnum;
use Kosv\DonationalertsClient\API\Resources\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\DatetimeFormatRule;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class GetListItem extends AbstractResource
{
    public function getAmount(): float
    {
        return $this->getContentValue('amount');
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $this->getContentValue('created_at'),
            new DateTimeZone('UTC')
        );
    }

    public function getCurrency(): string
    {
        return $this->getContentValue('currency');
    }

    public function getId(): int
    {
        return $this->getContentValue('id');
    }

    public function getIsShown(): int
    {
        return $this->getContentValue('is_shown');
    }

    public function getName(): string
    {
        return $this->getContentValue('name');
    }

    public function getMessage(): string
    {
        return $this->getContentValue('message');
    }

    public function getMessageType(): string
    {
        return $this->getContentValue('message_type');
    }

    public function getShownAt(): ?DateTimeImmutable
    {
        return $this->getContentValue('shown_at')
            ? DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $this->getContentValue('shown_at'),
                new DateTimeZone('UTC')
            )
            : null;
    }

    public function getUsername(): string
    {
        return $this->getContentValue('username');
    }

    protected function validateContent($content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                [
                    'id',
                    'name',
                    'username',
                    'message_type',
                    'message',
                    'amount',
                    'currency',
                    'is_shown',
                    'created_at',
                    'shown_at'
                ],
            ),
            new IsTypeRule('id', 'integer'),
            new IsTypeRule('name', 'string'),
            new IsTypeRule('username', 'string'),
            new IsTypeRule('message_type', 'string'),
            new IsTypeRule('message', 'string'),
            new IsTypeRule('amount', 'numeric'),
            new IsTypeRule('currency', 'string'),
            new IsTypeRule('is_shown', 'integer'),
            new IsTypeRule('created_at', 'string'),
            new IsTypeRule('shown_at', 'string', true),
            new InRule('currency', CurrencyEnum::getAll()),
            new DatetimeFormatRule('created_at', 'Y-m-d H:i:s'),
            new DatetimeFormatRule('shown_at', 'Y-m-d H:i:s', true),
        ]))->validate($content);
    }
}
