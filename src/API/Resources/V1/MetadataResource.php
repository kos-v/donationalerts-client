<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1;

use Kosv\DonationalertsClient\API\Resources\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsLrgOrEqualNumberRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class MetadataResource extends AbstractResource
{
    public function getCurrentPage(): int
    {
        return $this->getContentValue('current_page');
    }

    public function getPerPage(): int
    {
        return $this->getContentValue('per_page');
    }

    public function getTotalCount(): int
    {
        return $this->getContentValue('total');
    }

    protected function validateContent($content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                ['current_page', 'per_page', 'total'],
            ),
            new IsTypeRule('current_page', 'integer'),
            new IsTypeRule('per_page', 'integer'),
            new IsTypeRule('total', 'integer'),
            new IsLrgOrEqualNumberRule('current_page', 1),
            new IsLrgOrEqualNumberRule('per_page', 1),
            new IsLrgOrEqualNumberRule('total', 0),
        ]))->validate($content);
    }
}
