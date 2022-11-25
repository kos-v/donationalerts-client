<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\Users;

use Kosv\DonationalertsClient\API\Resources\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class ProfileInfo extends AbstractResource
{
    public function getAvatar(): string
    {
        return $this->getValue('avatar');
    }

    public function getCode(): string
    {
        return $this->getValue('code');
    }

    public function getEmail(): string
    {
        return $this->getValue('email');
    }

    public function getId(): int
    {
        return $this->getValue('id');
    }

    public function getName(): string
    {
        return $this->getValue('name');
    }

    public function getSocketConnectionToken(): string
    {
        return $this->getValue('socket_connection_token');
    }

    protected function getPayloadContentKey(): string
    {
        return 'data';
    }

    protected function validate(array $payload): ValidationErrors
    {
        return (new Validator([
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                ['id', 'code', 'name', 'avatar', 'email', 'socket_connection_token'],
            ),
            new IsTypeRule('id', 'integer'),
            new IsTypeRule('code', 'string'),
            new IsTypeRule('name', 'string'),
            new IsTypeRule('avatar', 'string'),
            new IsTypeRule('email', 'string'),
            new IsTypeRule('socket_connection_token', 'string'),
        ]))->validate($payload);
    }
}
