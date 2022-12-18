<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources\V1;

use function array_map;
use ArrayAccess;
use BadMethodCallException;
use function count;
use Countable;
use Kosv\DonationalertsClient\API\Resources\AbstractResource;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsListableArrayRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;
use OutOfRangeException;

abstract class AbstractCollection extends AbstractResource implements ArrayAccess, Countable
{
    /** @var array<AbstractResource> */
    private array $resources = [];

    public function __construct(array $content)
    {
        parent::__construct($content);
        $this->prepare();
    }

    public function count(): int
    {
        return count($this->resources);
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->resources[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetGet($offset): AbstractResource
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfRangeException("Index {$offset} does not exist");
        }
        return $this->resources[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('The collection is read-only');
    }

    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('The collection is read-only');
    }

    /**
     * @param mixed $content
     */
    abstract protected function makeItemResource($content): AbstractResource;

    protected function validateContent($content): ValidationErrors
    {
        return (new Validator([
            new IsListableArrayRule(KeysEnum::WHOLE_TARGET),
        ]))->validate($content);
    }

    private function prepare(): void
    {
        $this->resources = array_map(
            fn ($item) => $this->makeItemResource($item),
            $this->getContent()
        );
    }
}
