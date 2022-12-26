<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use Iterator;
use IteratorAggregate;
use Kosv\DonationalertsClient\API\Actions\AbstractAction;
use Kosv\DonationalertsClient\API\Client;

abstract class AbstractGetList extends AbstractAction implements IteratorAggregate
{
    /** @psalm-readonly */
    private Client $client;

    /**
     * @var positive-int
     * @psalm-readonly
     */
    private int $page;

    /**
     * @param positive-int $page
     */
    public function __construct(Client $client, int $page)
    {
        $this->client = $client;
        $this->page = $page;
    }

    final public function getAll(): array
    {
        return $this->getItems($this->makeIterator($this->client, $this->page, false));
    }

    final public function getAllOfPage(): array
    {
        return $this->getItems($this->makeIterator($this->client, $this->page, true));
    }

    final public function getIterator(): Iterator
    {
        return $this->makeIterator($this->client, $this->page, false);
    }

    /**
     * @param positive-int $page
     */
    abstract protected function makeIterator(Client $client, int $page, bool $onlyCurrentPage): Iterator;

    private function getItems(Iterator $iterator): array
    {
        $items = [];
        foreach ($iterator as $item) {
            $items[] = $item;
        }
        return $items;
    }
}
