<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

final class IsLrgOrEqualNumberRule extends AbstractRule
{
    private float $min;

    public function __construct(string $key, float $min, ?string $errMsg = self::ERR_MSG_DEFAULT)
    {
        $this->min = $min;
        parent::__construct($key, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value must be larger than or equal to {{min}}';
    }

    protected function validate($value): string
    {
        return $value < $this->min
            ? $this->makeErrorMessage(['min' => $this->min])
            : '';
    }
}
