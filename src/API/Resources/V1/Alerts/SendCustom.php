<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Alerts;

use DateTimeImmutable;
use DateTimeZone;
use Kosv\DonationalertsClient\API\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\DatetimeFormatRule;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class SendCustom extends AbstractResource
{
    public function getCreatedAt(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $this->getContentValue('created_at'),
            new DateTimeZone('UTC')
        );
    }

    public function getExternalId(): ?string
    {
        return $this->getContentValue('external_id');
    }

    public function getHeader(): ?string
    {
        return $this->getContentValue('header');
    }

    public function getId(): int
    {
        return $this->getContentValue('id');
    }

    public function getImageUrl(): ?string
    {
        return $this->getContentValue('image_url');
    }

    public function getIsShown(): int
    {
        return $this->getContentValue('is_shown');
    }

    public function getMessage(): ?string
    {
        return $this->getContentValue('message');
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

    public function getSoundUrl(): ?string
    {
        return $this->getContentValue('sound_url');
    }

    protected function validateContent(array $content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                [
                    'id', 'external_id', 'header',
                    'message', 'image_url', 'sound_url',
                    'is_shown', 'created_at', 'shown_at',
                ],
            ),
            new IsTypeRule('id', 'integer'),
            new IsTypeRule('external_id', 'string', true),
            new IsTypeRule('header', 'string', true),
            new IsTypeRule('message', 'string', true),
            new IsTypeRule('image_url', 'string', true),
            new IsTypeRule('sound_url', 'string', true),
            new IsTypeRule('is_shown', 'integer'),
            new IsTypeRule('created_at', 'string'),
            new IsTypeRule('shown_at', 'string', true),
            new InRule('is_shown', [0, 1]),
            new DatetimeFormatRule('created_at', 'Y-m-d H:i:s'),
            new DatetimeFormatRule('shown_at', 'Y-m-d H:i:s', true),
        ]))->validate($content);
    }
}
