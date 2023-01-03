<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use InvalidArgumentException;
use function is_string;
use function mb_strlen;

final class StringLenRule extends AbstractRule
{
    /** @psalm-readonly */
    private int $max;

    /** @psalm-readonly */
    private int $min;

    public function __construct(
        string $key,
        int $min,
        int $max,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->min = $min;
        $this->max = $max;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The length of the value string must be at least {{min}} and not more than {{max}}';
    }

    protected function validate($value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The argument $value must be string type');
        }

        return mb_strlen($value) >= $this->min && mb_strlen($value) <= $this->max
            ? ''
            : $this->makeErrorMessage([
                'min' => (string) $this->min,
                'max' => (string) $this->max
            ]);
    }
}
