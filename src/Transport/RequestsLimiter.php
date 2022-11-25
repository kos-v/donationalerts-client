<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Transport;

use function time;
use function sleep;

final class RequestsLimiter
{
    private ?int $lastRequestTime = null;
    private int $timeout;

    public function __construct(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function wait(): void
    {
        if ($this->timeout <= 0) {
            return;
        }

        if ($this->lastRequestTime !== null) {
            $expirationTime = $this->lastRequestTime + $this->timeout;
            $sleepTime = $expirationTime - time();
            if ($sleepTime > 0) {
                sleep($sleepTime);
            }
        }

        $this->lastRequestTime = time();
    }
}
