<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function count;
use InvalidArgumentException;
use OutOfBoundsException;

final class ValidationErrors
{
    /** @var array<string,array<RuleCheckResult>> */
    private array $errors = [];

    public function addError(RuleCheckResult $checkResult): void
    {
        if ($checkResult->isOk()) {
            throw new InvalidArgumentException('The result of checking a rule must contain an error');
        }

        if (!isset($this->errors[$checkResult->getKey()])) {
            $this->errors[$checkResult->getKey()] = [];
        }
        $this->errors[$checkResult->getKey()][] = $checkResult;
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
        foreach ($this->errors as $keyErrors) {
            if (count($keyErrors)) {
                return false;
            }
        }
        return true;
    }
}
