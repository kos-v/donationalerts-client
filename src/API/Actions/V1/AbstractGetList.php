<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use function ceil;
use Iterator;
use IteratorAggregate;
use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Resources\V1\Metadata;

abstract class AbstractGetList extends AbstractAction implements IteratorAggregate
{
    /** @psalm-readonly */
    private Metadata $metadata;

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
        $this->page = $page;
        $this->metadata = $this->makeMetadata($client, $this->page);

        parent::__construct($client);
    }

    final public function getAll(): array
    {
        return $this->getItems($this->makeIterator($this->getApiClient(), $this->page, false));
    }

    final public function getAllOfPage(): array
    {
        return $this->getItems($this->makeIterator($this->getApiClient(), $this->page, true));
    }

    final public function getIterator(): Iterator
    {
        return $this->makeIterator($this->getApiClient(), $this->page, false);
    }

    final public function getPageCount(): int
    {
        return (int)ceil($this->metadata->getTotalCount() / $this->metadata->getPerPage());
    }

    final public function getTotalCount(): int
    {
        return $this->metadata->getTotalCount();
    }

    /**
     * @param positive-int $page
     */
    abstract protected function makeIterator(Client $client, int $page, bool $onlyCurrentPage): Iterator;

    abstract protected function makeMetadata(Client $client, int $page): Metadata;

    private function getItems(Iterator $iterator): array
    {
        $items = [];
        foreach ($iterator as $item) {
            $items[] = $item;
        }
        return $items;
    }
}
