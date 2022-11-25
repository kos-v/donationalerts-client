<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth;

use Kosv\DonationalertsClient\Contracts\OAuthClientAuthorizeRequest;
use function parse_str;
use function parse_url;
use const PHP_URL_FRAGMENT;
use const PHP_URL_QUERY;

final class ClientAuthorizeRequest implements OAuthClientAuthorizeRequest
{
    /**
     * @var int|string|null[]
     */
    private array $queryParts = [];

    public function __construct(string $url)
    {
        $this->prepare($url);
    }

    public function getAccessToken(): ?string
    {
        return $this->queryParts['access_token'] ?? null;
    }

    public function getAccessTokenExpirationTime(): ?int
    {
        return isset($this->queryParts['expires_in'])
            ? (int)$this->queryParts['expires_in']
            : null;
    }

    public function getAccessTokenType(): ?string
    {
        return $this->queryParts['token_type'] ?? null;
    }

    public function getAuthorizeCode(): ?string
    {
        return $this->queryParts['code'] ?? null;
    }

    public function getError(): ?string
    {
        return $this->queryParts['error'] ?? null;
    }

    public function getState(): ?string
    {
        return $this->queryParts['state'] ?? null;
    }

    public function isAuthorizeCodeGrant(): bool
    {
        return $this->getAuthorizeCode() !== null;
    }

    public function isImplicitGrant(): bool
    {
        return $this->getAccessToken() !== null;
    }

    private function prepare(string $url): void
    {
        $params = parse_url($url, PHP_URL_FRAGMENT);
        if (!$params) {
            $params = parse_url($url, PHP_URL_QUERY);
        }
        if ($params) {
            parse_str($params, $this->queryParts);
        }
    }
}
