<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function array_map;
use function in_array;

final class InRule extends AbstractRule
{
    /**
     * @var array<mixed>
     * @psalm-readonly
     */
    private array $allowedValues;

    /**
     * @param array<mixed> $allowedValues
     */
    public function __construct(
        string $key,
        array $allowedValues,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->allowedValues = $allowedValues;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The value is not in the list of allowed values. Allowed values: [{{allowedValues}}]';
    }

    protected function validate($value): string
    {
        return !in_array($value, $this->allowedValues, true)
            ? $this->makeErrorMessage([
                'allowedValues' => array_map(
                    static fn ($item) => $item === null ? 'null' : (string)$item,
                    $this->allowedValues
                )
            ])
            : '';
    }
}
