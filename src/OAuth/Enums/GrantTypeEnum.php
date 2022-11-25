<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth\Enums;

final class GrantTypeEnum
{
    public const AUTHORIZATION_CODE = 'authorization_code';
    public const IMPLICIT = 'implicit';
    public const REFRESH_TOKEN = 'refresh_token';
}
