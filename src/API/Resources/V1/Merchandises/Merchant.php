<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Merchandises;

use Kosv\DonationalertsClient\API\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class Merchant extends AbstractResource
{
    public function getIdentifier(): string
    {
        /** @var string $identifier */
        $identifier = $this->getContentValue('identifier');
        return $identifier;
    }

    public function getName(): string
    {
        /** @var string $name */
        $name =  $this->getContentValue('name');
        return $name;
    }

    protected function validateContent(array $content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, ['identifier', 'name']),
            new IsTypeRule('identifier', 'string'),
            new IsTypeRule('name', 'string'),
        ]))->validate($content);
    }
}
