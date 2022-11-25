<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Contracts;

interface TransportResponse
{
    public function __toString(): string;
    public function getStatusCode(): int;
    public function isJson(): bool;
    public function toArray(): array;
}
