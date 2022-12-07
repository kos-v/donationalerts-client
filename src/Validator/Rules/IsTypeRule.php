<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function gettype;
use function is_numeric;
use function mb_strtolower;

final class IsTypeRule extends AbstractRule
{
    private const NUMERIC_TYPE = 'numeric';

    private string $expectedType;

    public function __construct(
        string $key,
        string $expectedType,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->expectedType = $expectedType;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value does not match the {{expectedType}} type';
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): string
    {
        $expectedType = mb_strtolower($this->expectedType);
        return ($expectedType === self::NUMERIC_TYPE && is_numeric($value)) ||
        (mb_strtolower(gettype($value)) === $expectedType)
            ? ''
            : $this->makeErrorMessage(['expectedType' => $expectedType]);
    }
}
