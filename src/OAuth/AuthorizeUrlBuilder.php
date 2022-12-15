<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth;

use function http_build_query;
use function implode;
use function in_array;
use InvalidArgumentException;
use Kosv\DonationalertsClient\OAuth\Enums\GrantTypeEnum;
use Kosv\DonationalertsClient\OAuth\Enums\ScopeEnum;

final class AuthorizeUrlBuilder
{
    private const BASE_URL = 'https://www.donationalerts.com/oauth/authorize';
    private const RESPONSE_TYPE_CODE = 'code';
    private const RESPONSE_TYPE_TOKEN = 'token';

    /** @psalm-readonly */
    private int $clientId;

    /** @psalm-readonly */
    private string $grantType;

    /** @psalm-readonly */
    private string $redirectUri;

    /**
     * @var array<ScopeEnum::*>
     * @psalm-readonly
     */
    private array $scopes;

    /**
     * @param GrantTypeEnum::AUTHORIZATION_CODE | GrantTypeEnum::IMPLICIT $grantType
     * @param array<ScopeEnum::*> $scopes
     */
    public function __construct(string $grantType, int $clientId, string $redirectUri, array $scopes)
    {
        $this->grantType = $grantType;
        $this->clientId = $clientId;
        $this->redirectUri = $redirectUri;
        $this->scopes = $scopes;
    }

    public function build(): string
    {
        if (!in_array($this->grantType, [
            GrantTypeEnum::AUTHORIZATION_CODE,
            GrantTypeEnum::IMPLICIT
        ], true)) {
            throw new InvalidArgumentException("Unsupported grant type \"{$this->grantType}\"");
        }
        return $this->_build();
    }

    private function _build(): string
    {
        return self::BASE_URL . '?' . http_build_query([
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'response_type' => GrantTypeEnum::AUTHORIZATION_CODE === $this->grantType
                    ? self::RESPONSE_TYPE_CODE
                    : self::RESPONSE_TYPE_TOKEN,
                'scope' => implode(' ', $this->scopes),
            ]);
    }
}
