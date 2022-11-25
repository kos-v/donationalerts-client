<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth;

final class Config
{
    public int $clientId;
    public string $clientSecret;
    public string $redirectUri;

    /**
     * @param string[] $scopes
     */
    public function __construct(int $clientId, string $clientSecret, string $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }
}
