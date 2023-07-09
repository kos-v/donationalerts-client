<?php


declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\__TestTools;

use function array_slice;
use function count;
use function json_encode;
use const JSON_THROW_ON_ERROR;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Transport\Response;
use LogicException;

final class PaginableRequestTransportStub implements TransportClient
{
    private array $items = [];
    private int $perPage;

    public function __construct(int $totalCount, int $perPage, callable $makeItem)
    {
        $this->perPage = $perPage;
        for ($i = 0; $i < $totalCount; $i++) {
            $this->items[] = $makeItem($i + 1);
        }
    }

    public function get(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        $offset = ($payload['page'] - 1) * $this->perPage;
        $limit = $this->perPage;

        return new Response(json_encode([
            'data' => array_slice($this->items, $offset, $limit),
            'meta' => [
                'current_page' => $payload['page'],
                'per_page' => $this->perPage,
                'total' => count($this->items)
            ]
        ], JSON_THROW_ON_ERROR), 200);
    }

    public function post(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        throw new LogicException('This method should not have been called');
    }

    public function put(string $url, array $payload = [], array $headers = []): TransportResponse
    {
        throw new LogicException('This method should not have been called');
    }
}
