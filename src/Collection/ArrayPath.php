<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Collection;

use function array_map;
use function is_array;
use function is_numeric;
use function is_string;
use function mb_ereg_replace;
use function mb_split;
use UnexpectedValueException;

/**
 * @template FullPath of mixed
 */
final class ArrayPath
{
    /**
     * @var FullPath
     * @psalm-readonly
     */
    private $fullPath;

    /**
     * @param FullPath $fullPath
     */
    public function __construct($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * @return FullPath
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @return list<mixed>
     */
    public function toParts(): array
    {
        if (!is_string($this->fullPath)) {
            return [$this->fullPath];
        }

        $parseResult = mb_split('(?<!\\\\)\\.', $this->fullPath);
        if (!is_array($parseResult)) {
            throw new UnexpectedValueException('When parsing the data, an unexpected result was obtained');
        }

        return array_map(
            fn (string $item) => is_numeric($item) ? $item + 0 : $this->unescapePath($item),
            $parseResult
        );
    }

    private function unescapePath(string $path): string
    {
        $path = mb_ereg_replace('\\\\(?=\\.)', '', $path);
        if (!is_string($path)) {
            throw new UnexpectedValueException('When unescaping, an unexpected result was obtained');
        }

        return $path;
    }
}
