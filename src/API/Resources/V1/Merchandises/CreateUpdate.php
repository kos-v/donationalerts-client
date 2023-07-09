<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1\Merchandises;

use function array_key_exists;
use DateTimeImmutable;
use DateTimeZone;
use function is_array;
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

final class CreateUpdate extends AbstractResource
{
    public function getCurrency(): string
    {
        /** @var string $currency */
        $currency = $this->getContentValue('currency');
        return $currency;
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        /** @var string|null $endAtRaw */
        $endAtRaw = $this->getContentValue('end_at');

        /** @var DateTimeImmutable|null $endAt */
        $endAt = $endAtRaw
            ? DateTimeImmutable::createFromFormat(
                'Y-m-d H.i.s',
                $endAtRaw,
                new DateTimeZone('UTC')
            )
            : null;

        return $endAt;
    }

    public function getId(): int
    {
        /** @var int $id */
        $id = $this->getContentValue('id');
        return $id;
    }

    public function getIdentifier(): string
    {
        /** @var string $identifier */
        $identifier = $this->getContentValue('identifier');
        return $identifier;
    }

    public function getImgUrl(): ?string
    {
        /** @var string|null $imgUrl */
        $imgUrl = $this->getContentValue('img_url');
        return $imgUrl;
    }

    public function getIsActive(): int
    {
        /** @var int $isActive */
        $isActive = $this->getContentValue('is_active');
        return $isActive;
    }

    public function getIsPercentage(): int
    {
        /** @var int $isPercentage */
        $isPercentage = $this->getContentValue('is_percentage');
        return $isPercentage;
    }

    public function getMerchant(): Merchant
    {
        /** @var Merchant $merchant */
        $merchant = $this->getContentValue('merchant');
        return $merchant;
    }

    public function getPriceService(): float
    {
        return (float)$this->getContentValue('price_service');
    }

    public function getPriceUser(): float
    {
        return (float)$this->getContentValue('price_user');
    }

    public function getTitle(): Title
    {
        /** @var Title $title */
        $title =  $this->getContentValue('title');
        return $title;
    }

    public function getUrl(): ?string
    {
        /** @var string|null $url */
        $url =  $this->getContentValue('url');
        return $url;
    }

    protected function prepareContentBeforeValidate(array $content): array
    {
        $content = parent::prepareContentBeforeValidate($content);

        if (array_key_exists('merchant', $content) && is_array($content['merchant'])) {
            $content['merchant'] = new Merchant($content['merchant']);
        }
        if (array_key_exists('title', $content) && is_array($content['title'])) {
            $content['title'] = new Title($content['title']);
        }

        return $content;
    }

    protected function validateContent(array $content): ValidationErrors
    {
        return (new Validator([
            new IsKeyableArrayRule(KeysEnum::WHOLE_TARGET),
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [
                'id', 'merchant', 'identifier', 'title',
                'is_active', 'is_percentage', 'currency', 'price_user',
                'price_service', 'url', 'img_url', 'end_at',
            ]),
            new IsTypeRule('id', 'integer'),
            new IsTypeRule('merchant', 'object'),
            new IsTypeRule('identifier', 'string'),
            new IsTypeRule('title', 'object'),
            new IsTypeRule('is_active', 'integer'),
            new IsTypeRule('is_percentage', 'integer'),
            new IsTypeRule('currency', 'string'),
            new IsTypeRule('price_user', 'numeric'),
            new IsTypeRule('price_service', 'numeric'),
            new IsTypeRule('url', 'string', true),
            new IsTypeRule('img_url', 'string', true),
            new IsTypeRule('end_at', 'string', true),
            new InRule('is_active', [0, 1]),
            new InRule('is_percentage', [0, 1]),
            new InRule('currency', CurrencyEnum::getAll()),
            new DatetimeFormatRule('end_at', 'Y-m-d H.i.s', true),
        ]))->validate($content);
    }
}
