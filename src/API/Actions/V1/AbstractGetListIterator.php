<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1;

use function count;
use InvalidArgumentException;
use Iterator;
use Kosv\DonationalertsClient\API\Actions\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\API\Resources\V1\AbstractCollection;
use Kosv\DonationalertsClient\API\Resources\V1\Metadata;
use Kosv\DonationalertsClient\API\Response;

abstract class AbstractGetListIterator extends AbstractAction implements Iterator
{
    /** @psalm-readonly */
    private Client $client;

    private int $currentItemIndex = -1;

    private ?AbstractCollection $items = null;

    private ?Metadata $metadata = null;

    /** @psalm-readonly */
    private bool $onlyStartPage;

    /**
     * @var positive-int
     * @psalm-readonly
     */
    private int $startPage;

    /**
     * @param positive-int $startPage
     */
    public function __construct(Client $client, int $startPage, bool $onlyStartPage = false)
    {
        if ($startPage < 1) {
            throw new InvalidArgumentException('The value of the $startPage argument must be positive');
        }

        $this->client = $client;
        $this->startPage = $startPage;
        $this->onlyStartPage = $onlyStartPage;
    }

    final public function current()
    {
        return $this->items[$this->currentItemIndex % $this->metadata->getPerPage()];
    }

    final public function next()
    {
        $this->currentItemIndex++;

        if ($this->isNeedLoadNextPage()) {
            $response = $this->requestItems($this->client, $this->metadata->getCurrentPage() + 1);
            $resourceExtractor = new RawResourceExtractor($response->toArray());
            $this->items = $this->extractItems($resourceExtractor);
            $this->metadata = $this->extractMetadata($resourceExtractor);
        }
    }

    final public function key()
    {
        return $this->currentItemIndex;
    }

    final public function valid()
    {
        if (!$this->isLoadedResources()) {
            return false;
        }

        return $this->onlyStartPage
            ? $this->isCurrentItemInStartPageRange()
            : $this->isCurrentItemInTotalRange();
    }

    final public function rewind()
    {
        $this->items = null;
        $this->metadata = null;
        $this->currentItemIndex = -1;

        $response = $this->requestItems($this->client, $this->startPage);
        $resourceExtractor = new RawResourceExtractor($response->toArray());
        if (count($items = $this->extractItems($resourceExtractor))) {
            $this->items = $items;
            $this->metadata = $this->extractMetadata($resourceExtractor);
            $this->currentItemIndex = ($this->startPage - 1) * $this->metadata->getPerPage();
        }
    }

    abstract protected function extractItems(RawResourceExtractor $extractor): AbstractCollection;

    abstract protected function extractMetadata(RawResourceExtractor $extractor): Metadata;

    abstract protected function requestItems(Client $client, int $page): Response;

    private function isNeedLoadNextPage(): bool
    {
        return !$this->onlyStartPage
            && $this->currentItemIndex >= $this->metadata->getCurrentPage() * $this->metadata->getPerPage()
            && $this->currentItemIndex < $this->metadata->getTotalCount();
    }

    private function isCurrentItemInStartPageRange(): bool
    {
        $startItemIndex = ($this->metadata->getCurrentPage() - 1) * $this->metadata->getPerPage();
        return $this->currentItemIndex >= $startItemIndex
            && $this->currentItemIndex < $startItemIndex + count($this->items);
    }

    private function isCurrentItemInTotalRange(): bool
    {
        return $this->currentItemIndex >= 0
            && $this->currentItemIndex < $this->metadata->getTotalCount();
    }

    private function isLoadedResources(): bool
    {
        return $this->items !== null && $this->metadata !== null;
    }
}
