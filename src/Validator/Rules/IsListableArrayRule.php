<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function array_keys;
use function is_array;
use function is_int;

final class IsListableArrayRule extends AbstractRule
{
    protected function getDefaultError(): string
    {
        return 'The value must be listable array type';
    }

    protected function validate($value): string
    {
        if (!is_array($value)) {
            return $this->makeErrorMessage();
        }

        foreach (array_keys($value) as $key) {
            if (!is_int($key)) {
                return $this->makeErrorMessage();
            }
        }

        return '';
    }
}
