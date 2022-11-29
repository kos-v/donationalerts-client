<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function array_keys;
use function is_array;
use function is_string;

final class IsKeyableArrayRule extends AbstractRule
{
    protected function getDefaultError(): string
    {
        return 'The value must be keyable array type';
    }

    protected function validate($value): string
    {
        if (!is_array($value)) {
            return $this->makeErrorMessage();
        }

        foreach (array_keys($value) as $key) {
            if (!is_string($key)) {
                return $this->makeErrorMessage();
            }
        }

        return '';
    }
}
