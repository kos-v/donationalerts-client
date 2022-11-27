<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use Kosv\DonationalertsClient\Validator\Rule;
use Kosv\DonationalertsClient\Validator\RuleCheckResult;

abstract class AbstractRule implements Rule
{
    public const ERR_MSG_DEFAULT = null;

    private ?string $errorMessage;
    private string $key;

    public function __construct(string $key, ?string $errMsg = self::ERR_MSG_DEFAULT)
    {
        $this->key = $key;
        $this->errorMessage = $errMsg;
    }

    public function check($value): RuleCheckResult
    {
        $error = $this->validate($value);
        return new RuleCheckResult($this->key, $error === '', $error);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    abstract protected function getDefaultError(): string;

    final protected function makeErrorMessage(array $vars): string
    {
        $errorMessage = $this->errorMessage;
        if ($errorMessage === self::ERR_MSG_DEFAULT) {
            $errorMessage = $this->getDefaultError();
        }

        foreach ($vars as $key => $val) {
            $errorMessage = mb_ereg_replace(
                "\\{\\{{$key}\\}\\}",
                is_array($val) ? implode(', ', $val) : $val,
                $errorMessage
            );
        }

        return $errorMessage;
    }

    /**
     * @param mixed $value
     */
    abstract protected function validate($value): string;
}
