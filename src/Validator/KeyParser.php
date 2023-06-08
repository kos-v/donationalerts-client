<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function array_map;
use function ctype_digit;
use function is_array;
use function is_string;
use function mb_ereg_replace;
use function mb_split;
use UnexpectedValueException;

class KeyParser
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function parseToDirectionalList(): array
    {
        if (!$this->key) {
            return [];
        }

        $parseResult = mb_split('(?<!\\\\)\\.', $this->key);
        if (!is_array($parseResult)) {
            throw new UnexpectedValueException('When parsing the data, an unexpected result was obtained');
        }

        return array_map(
            fn (string $item) => ctype_digit($item) ? (int)$item : $this->unescapeKey($item),
            $parseResult
        );
    }

    private function unescapeKey(string $key): string
    {
        $key = mb_ereg_replace('\\\\(?=\\.)', '', $key);
        if (!is_string($key)) {
            throw new UnexpectedValueException('When unescaping, an unexpected result was obtained');
        }

        return $key;
    }
}
