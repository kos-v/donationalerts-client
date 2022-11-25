<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Exceptions\API;

use Kosv\DonationalertsClient\Exceptions\ServerExceptionTrait;

class ServerException extends Exception
{
    use ServerExceptionTrait;
}
