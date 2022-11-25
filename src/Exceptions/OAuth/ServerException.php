<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Exceptions\OAuth;

use Kosv\DonationalertsClient\Exceptions\ServerExceptionTrait;

class ServerException extends Exception
{
    use ServerExceptionTrait;
}
