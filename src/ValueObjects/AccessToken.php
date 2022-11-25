<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\ValueObjects;

final class AccessToken
{
    private int $expirationTime;
    private string $token;
    private string $refreshToken;
    private string $type;

    public function __construct(string $token, int $expirationTime, string $type, string $refreshToken = '')
    {
        $this->token = $token;
        $this->expirationTime = $expirationTime;
        $this->type = $type;
        $this->refreshToken = $refreshToken;
    }

    public function __toString(): string
    {
        return $this->getToken();
    }

    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
