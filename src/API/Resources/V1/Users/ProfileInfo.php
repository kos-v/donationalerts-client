<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Users;

use Kosv\DonationalertsClient\API\Resources\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class ProfileInfo extends AbstractResource
{
    public function getAvatar(): string
    {
        return $this->getContentValue('avatar');
    }

    public function getCode(): string
    {
        return $this->getContentValue('code');
    }

    public function getEmail(): string
    {
        return $this->getContentValue('email');
    }

    public function getId(): int
    {
        return $this->getContentValue('id');
    }

    public function getName(): string
    {
        return $this->getContentValue('name');
    }

    public function getSocketConnectionToken(): string
    {
        return $this->getContentValue('socket_connection_token');
    }

    protected function validateContent($content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
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
        ]))->validate($content);
    }
}
