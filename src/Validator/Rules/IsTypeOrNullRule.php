<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

final class IsTypeOrNullRule extends AbstractRule
{
    private string $expectedType;
    private IsTypeRule $typeRule;

    public function __construct(string $key, string $expectedType, ?string $errMsg = self::ERR_MSG_DEFAULT)
    {
        $this->expectedType = $expectedType;
        $this->typeRule = new IsTypeRule($key, $expectedType);
        parent::__construct($key, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value does not match the {{expectedType}} or null type';
    }

    protected function validate($value): string
    {
        if ($value === null) {
            return '';
        }

        return !$this->typeRule->check($value)->isOk()
            ? $this->makeErrorMessage(['expectedType' => $this->expectedType])
            : '';
    }
}
