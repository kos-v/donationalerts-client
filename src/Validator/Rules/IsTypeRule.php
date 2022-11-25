<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function gettype;
use function mb_strtolower;

final class IsTypeRule extends AbstractRule
{
    private string $expectedType;

    public function __construct(string $key, string $expectedType, ?string $errMsg = self::ERR_MSG_DEFAULT)
    {
        $this->expectedType = $expectedType;
        parent::__construct($key, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value does not match the {{expectedType}} type';
    }

    protected function validate($value): string
    {
        $expectedType = mb_strtolower($this->expectedType);
        $valueType = mb_strtolower(gettype($value));
        return $valueType !== $expectedType
            ? $this->makeErrorMessage(['expectedType' => $expectedType])
            : '';
    }
}
