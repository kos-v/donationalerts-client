<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Transport;

use Kosv\DonationalertsClient\Contracts\TransportResponse;
use JsonException;
use function json_decode;
use const JSON_THROW_ON_ERROR;

final class Response implements TransportResponse
{
    private ?array $jsonResponse = null;
    private string $rawResponse;
    private int $statusCode;

    public function __construct(string $response, int $statusCode)
    {
        $this->rawResponse = $response;
        $this->statusCode = $statusCode;
    }

    public function __toString(): string
    {
        return $this->rawResponse;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isJson(): bool
    {
        try {
            $this->decodeToJson();
        } catch (JsonException $e) {
            return false;
        }
        return true;
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        $this->decodeToJson();
        return $this->jsonResponse;
    }

    /**
     * @throws JsonException
     */
    private function decodeToJson(): void
    {
        if ($this->jsonResponse !== null) {
            return;
        }
        $this->jsonResponse = json_decode($this->rawResponse, true, 512, JSON_THROW_ON_ERROR);
    }
}