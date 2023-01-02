<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\ValidationErrors;

abstract class AbstractPayload
{
    private array $payload;

    public function __construct(array $payload)
    {
        $this->prepare($payload);
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    abstract protected function validatePayload(array $payload): ValidationErrors;

    private function prepare(array $payload): void
    {
        $errors = $this->validatePayload($payload);
        if (!$errors->isEmpty()) {
            throw new InvalidArgumentException(
                "The param {$errors->getFirstError()->getKey()} is not valid. {$errors->getFirstError()->getError()}"
            );
        }
        $this->payload = $payload;
    }
}
