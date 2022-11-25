<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Transport\Exceptions;

use Exception;
use Kosv\DonationalertsClient\Contracts\TransportClientError;

final class TransportClientException extends Exception implements TransportClientError
{
}
