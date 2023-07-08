<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function array_slice;
use function count;
use function in_array;
use InvalidArgumentException;
use Kosv\DonationalertsClient\Collection\ArrayPath;

class Key
{
    /** @psalm-readonly */
    private ArrayPath $arrayPath;

    public function __construct(string $key)
    {
        if (!static::isValidRawKey($key)) {
            throw new InvalidArgumentException('The value of the $key argument is not valid');
        }

        $this->arrayPath = new ArrayPath($key);
    }

    public function __toString(): string
    {
        /** @var string $path */
        $path = $this->arrayPath->getFullPath();
        return $path;
    }

    /**
     * @return int|string
     */
    public function getLastPart()
    {
        $parts = $this->toParts();
        return $parts[count($parts) - 1];
    }

    /**
     * @return list<int|string>
     */
    public function toParts(): array
    {
        /** @var list<int|string> $parts */
        $parts = $this->arrayPath->toParts();
        return $parts;
    }

    private static function isValidRawKey(string $key): bool
    {
        $keyParts = (new ArrayPath($key))->toParts();
        return !in_array(
            KeysEnum::ALL_IN_LIST,
            array_slice($keyParts, 0, count($keyParts) - 1),
            true
        );
    }
}
