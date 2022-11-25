<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Contracts;

interface OAuthClientAuthorizeRequest
{
    public function getAccessToken(): ?string;
    public function getAccessTokenExpirationTime(): ?int;
    public function getAccessTokenType(): ?string;
    public function getAuthorizeCode(): ?string;
    public function getError(): ?string;
    public function getState(): ?string;
    public function isAuthorizeCodeGrant(): bool;
    public function isImplicitGrant(): bool;
}
