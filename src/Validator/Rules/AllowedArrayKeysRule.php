<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function array_diff;
use function array_keys;
use function count;
use InvalidArgumentException;
use function is_array;

final class AllowedArrayKeysRule extends AbstractRule
{
    /**
     * @var array<int|string> $allowedArrayKeys
     * @psalm-readonly
     */
    private array $allowedArrayKeys;

    /**
     * @param array<int|string> $allowedArrayKeys
     */
    public function __construct(
        string $key,
        array $allowedArrayKeys,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->allowedArrayKeys = $allowedArrayKeys;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The key is not in the list of allowed array keys. Allowed keys: [{{allowedKeys}}]';
    }

    protected function validate($value): string
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('The argument $value must be array type');
        }

        return count(array_diff(array_keys($value), $this->allowedArrayKeys)) > 0
            ? $this->makeErrorMessage([
                'allowedKeys' => array_map(
                    static fn ($item) => (string)$item,
                    $this->allowedArrayKeys
                )
            ])
            : '';
    }
}
