<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Exceptions\OAuth;

final class InvalidAccessTokenRequestException extends ServerException
{
    private array $response;

    public function __construct(array $response, int $statusCode)
    {
        $this->response = $response;
        parent::__construct(
            $this->response['hint'] ?? $this->response['error_description'] ?? $this->response['error'] ?? '',
            $statusCode
        );
    }

    public function getErrorCode(): string
    {
        return $this->response['error'] ?? '';
    }

    public function getErrorDescription(): string
    {
        return $this->response['error_description'] ?? '';
    }
}
