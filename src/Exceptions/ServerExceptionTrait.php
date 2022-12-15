<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Exceptions;

use function mb_strlen;
use function mb_substr;
use function sprintf;

trait ServerExceptionTrait
{
    /** @psalm-readonly */
    private int $statusCode;

    public function __construct(string $error, int $statusCode, string $response = '')
    {
        $this->statusCode = $statusCode;
        parent::__construct($this->prepareErrorMessage($error, $response));
    }

    final public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private function prepareErrorMessage(string $error, string $response = ''): string
    {
        if ($response) {
            if (mb_strlen($response) > 1024) {
                $response = mb_substr($response, 0, 1024) . '...';
            }
            $error = sprintf('%s. Response: %s', $error, $response);
        }
        return $error;
    }
}
