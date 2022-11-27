<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function count;
use OutOfBoundsException;

final class ValidationErrors
{
    /** @var array<string,array<RuleCheckResult>> */
    private array $errors = [];

    public function addError(string $key, RuleCheckResult $error): void
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }
        $this->errors[$key][] = $error;
    }

    public function getFirstError(): RuleCheckResult
    {
        foreach ($this->errors as $keyErrors) {
            if (count($keyErrors)) {
                return $keyErrors[0];
            }
        }

        throw new OutOfBoundsException(
            'Object not contains errors. Use the isEmpty method before getting an error'
        );
    }

    public function isEmpty(): bool
    {
        return count($this->errors) === 0;
    }
}
