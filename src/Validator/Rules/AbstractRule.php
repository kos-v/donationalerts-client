<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function implode;
use function is_array;
use Kosv\DonationalertsClient\Validator\Rule;
use Kosv\DonationalertsClient\Validator\RuleCheckResult;
use function mb_ereg_replace;

abstract class AbstractRule implements Rule
{
    public const ERR_MSG_DEFAULT = null;

    /** @psalm-readonly */
    private ?string $errorMessage;

    /** @psalm-readonly */
    private string $key;

    /** @psalm-readonly */
    private bool $nullable;

    public function __construct(string $key, bool $nullable = false, ?string $errMsg = self::ERR_MSG_DEFAULT)
    {
        $this->key = $key;
        $this->nullable = $nullable;
        $this->errorMessage = $errMsg;
    }

    public function check($value): RuleCheckResult
    {
        if ($this->nullable && $value === null) {
            return new RuleCheckResult($this->key, true, '');
        }

        $error = $this->validate($value);
        return new RuleCheckResult($this->key, $error === '', $error);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    abstract protected function getDefaultError(): string;

    /**
     * @param array<string,string|array<string>> $vars
     */
    final protected function makeErrorMessage(array $vars = []): string
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
