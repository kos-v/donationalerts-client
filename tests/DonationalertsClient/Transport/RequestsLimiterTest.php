<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Transport;

use Kosv\DonationalertsClient\Transport\RequestsLimiter;
use PHPUnit\Framework\TestCase;
use function microtime;

final class RequestsLimiterTest extends TestCase
{
    public function testWaitWhenFirstRun(): void
    {
        $limiter = new RequestsLimiter(1);
        $startTime = microtime(true);
        $limiter->wait();
        $result = microtime(true) - $startTime;
        $this->assertLessThan(1, $result);
    }

    public function testWaitWhenTimeoutDisable(): void
    {
        $limiter1 = new RequestsLimiter(0);
        $limiter1->wait();
        $startTime1 = microtime(true);
        $limiter1->wait();
        $result1 = microtime(true) - $startTime1;
        $this->assertLessThan(1, $result1);

        $limiter2 = new RequestsLimiter(-1);
        $limiter2->wait();
        $startTime2 = microtime(true);
        $limiter2->wait();
        $result2 = microtime(true) - $startTime2;
        $this->assertLessThan(1, $result2);
    }

    public function testWaitWhenTimeout1sec(): void
    {
        $limiter = new RequestsLimiter(1);
        $limiter->wait();

        $startTime1 = microtime(true);
        $limiter->wait();
        $result1 = microtime(true) - $startTime1;
        $this->assertGreaterThanOrEqual(1, $result1);
        $this->assertLessThan(2, $result1);

        $startTime2 = microtime(true);
        $limiter->wait();
        $result2 = microtime(true) - $startTime2;
        $this->assertGreaterThanOrEqual(1, $result2);
        $this->assertLessThan(2, $result2);
    }
}
