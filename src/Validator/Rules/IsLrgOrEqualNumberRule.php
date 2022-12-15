<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use InvalidArgumentException;
use function is_numeric;

final class IsLrgOrEqualNumberRule extends AbstractRule
{
    /** @psalm-readonly */
    private float $min;

    public function __construct(
        string $key,
        float $min,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->min = $min;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value must be larger than or equal to {{min}}';
    }

    protected function validate($value): string
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('The $value argument must be a numeric type');
        }

        return $value < $this->min
            ? $this->makeErrorMessage(['min' => (string)$this->min])
            : '';
    }
}
