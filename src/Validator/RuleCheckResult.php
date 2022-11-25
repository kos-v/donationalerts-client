<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

final class RuleCheckResult
{
    private string $error;
    private string $key;
    private bool $ok;

    public function __construct(string $key, bool $ok, string $error = '')
    {
        $this->key = $key;
        $this->ok = $ok;
        $this->error = $error;
    }

    public function __toString(): string
    {
        return $this->getError();
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isOk(): bool
    {
        return $this->ok;
    }
}
