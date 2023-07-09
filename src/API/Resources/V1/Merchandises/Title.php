<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Merchandises;

use Kosv\DonationalertsClient\API\AbstractResource;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class Title extends AbstractResource
{
    public function getBelarusian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::BELARUSIAN);
        return $title;
    }

    public function getChinese(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::CHINESE);
        return $title;
    }

    public function getEnglishUsa(): string
    {
        /** @var string $title */
        $title = $this->getContentValue(LangEnum::ENGLISH_USA);
        return $title;
    }

    public function getEstonian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::ESTONIAN);
        return $title;
    }

    public function getFrench(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::FRENCH);
        return $title;
    }

    public function getGeorgian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::GEORGIAN);
        return $title;
    }

    public function getGerman(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::GERMAN);
        return $title;
    }

    public function getHebrew(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::HEBREW);
        return $title;
    }

    public function getItalian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::ITALIAN);
        return $title;
    }

    public function getKazakh(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::KAZAKH);
        return $title;
    }

    public function getKorean(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::KOREAN);
        return $title;
    }

    public function getLatvian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::LATVIAN);
        return $title;
    }

    public function getPolish(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::POLISH);
        return $title;
    }

    public function getPortugueseBrazil(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::PORTUGUESE_BRAZIL);
        return $title;
    }

    public function getRussian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::RUSSIAN);
        return $title;
    }

    public function getSpanish(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::SPANISH);
        return $title;
    }

    public function getSpanishUsa(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::SPANISH_USA);
        return $title;
    }

    public function getSwedish(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::SWEDISH);
        return $title;
    }

    public function getTurkish(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::TURKISH);
        return $title;
    }

    public function getUkrainian(): ?string
    {
        /** @var string|null $title */
        $title = $this->getContentValue(LangEnum::UKRAINIAN);
        return $title;
    }

    protected function validateContent(array $content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [LangEnum::ENGLISH_USA]),
            new IsTypeRule(LangEnum::ENGLISH_USA, 'string'),
            new IsTypeRule(LangEnum::BELARUSIAN, 'string', true),
            new IsTypeRule(LangEnum::GERMAN, 'string', true),
            new IsTypeRule(LangEnum::SPANISH, 'string', true),
            new IsTypeRule(LangEnum::SPANISH_USA, 'string', true),
            new IsTypeRule(LangEnum::ESTONIAN, 'string', true),
            new IsTypeRule(LangEnum::FRENCH, 'string', true),
            new IsTypeRule(LangEnum::HEBREW, 'string', true),
            new IsTypeRule(LangEnum::ITALIAN, 'string', true),
            new IsTypeRule(LangEnum::GEORGIAN, 'string', true),
            new IsTypeRule(LangEnum::KAZAKH, 'string', true),
            new IsTypeRule(LangEnum::KOREAN, 'string', true),
            new IsTypeRule(LangEnum::LATVIAN, 'string', true),
            new IsTypeRule(LangEnum::POLISH, 'string', true),
            new IsTypeRule(LangEnum::PORTUGUESE_BRAZIL, 'string', true),
            new IsTypeRule(LangEnum::RUSSIAN, 'string', true),
            new IsTypeRule(LangEnum::SWEDISH, 'string', true),
            new IsTypeRule(LangEnum::TURKISH, 'string', true),
            new IsTypeRule(LangEnum::UKRAINIAN, 'string', true),
            new IsTypeRule(LangEnum::CHINESE, 'string', true),
        ]))->validate($content);
    }
}
