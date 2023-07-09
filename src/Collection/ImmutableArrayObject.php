<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Collection;

use function array_key_exists;
use function array_pop;
use function is_array;
use function is_string;

final class ImmutableArrayObject
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * @param array<array-key, mixed> $keysValues
     */
    public function set(array $keysValues): self
    {
        $array = $this->array;

        foreach ($keysValues as $key => $value) {
            if (is_string($key)) {
                $subarrayRef = &$array;

                foreach ((new ArrayPath($key))->toParts() as $arrayPathPart) {
                    if (!array_key_exists($arrayPathPart, $subarrayRef) || !is_array($subarrayRef[$arrayPathPart])) {
                        $subarrayRef[$arrayPathPart] = [];
                    }
                    $subarrayRef = &$subarrayRef[$arrayPathPart];
                }

                $subarrayRef = $value;
            } else {
                $array[$key] = $value;
            }
        }

        return new static($array);
    }

    /**
     * @param array-key ...$keys
     */
    public function unset(...$keys): self
    {
        $array = $this->array;
        foreach ($keys as $key) {
            if (is_string($key)) {
                $arrayPathParts = (new ArrayPath($key))->toParts();
                $lastArrayPathPart = array_pop($arrayPathParts);

                $subarrayRef = &$array;
                $lastSubarrayNotFound = false;
                foreach ($arrayPathParts as  $arrayPathPart) {
                    if (!is_array($subarrayRef) || !array_key_exists($arrayPathPart, $subarrayRef)) {
                        $lastSubarrayNotFound = true;
                        break;
                    }

                    $subarrayRef = &$subarrayRef[$arrayPathPart];
                }

                if (!$lastSubarrayNotFound) {
                    unset($subarrayRef[$lastArrayPathPart]);
                }
            } else {
                unset($array[$key]);
            }
        }

        return new static($array);
    }
}
