<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Exceptions\OAuth;

use Kosv\DonationalertsClient\OAuth\Enums\ResponseErrorEnum;
use Throwable;

final class AccessDeniedException extends Exception
{
    public function __construct(
        string $message = "OAuth error: " . ResponseErrorEnum::ACCESS_DENIED,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
