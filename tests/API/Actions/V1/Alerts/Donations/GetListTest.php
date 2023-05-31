<?php

declare(strict_types=1);

namespace API\Actions\V1\Alerts\Donations;

use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations\GetList;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Config;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Tests\__TestTools\PaginableRequestTransportStub;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use PHPUnit\Framework\TestCase;
use function range;

final class GetListTest extends TestCase
{
    /**
     * @dataProvider getAllDataProvider
     */
    public function testGetAll(int $totalCount, int $page, array $expectedIdsRange): void
    {
        $getList = new GetList($this->makeApiClient($this->makeTransportStub($totalCount, 2)), $page);
        $ids = [];
        foreach ($getList->getAll() as $item) {
            $ids[] = $item->getId();
        }
        $this->assertEquals($expectedIdsRange ? range(...$expectedIdsRange) : [], $ids);
    }

    public function getAllDataProvider(): array
    {
        return [
            [0, 1, []],
            [1, 1, [1, 1]],
            [7, 1, [1, 7]],
            [7, 2, [3, 7]],
            [7, 3, [5, 7]],
            [7, 4, [7, 7]],
            [7, 5, []],
        ];
    }

    /**
     * @dataProvider getAllOfPageDataProvider
     */
    public function testGetAllOfPage(int $totalCount, int $page, array $expectedIdsRange): void
    {
        $getList = new GetList($this->makeApiClient($this->makeTransportStub($totalCount, 2)), $page);
        $ids = [];
        foreach ($getList->getAllOfPage() as $item) {
            $ids[] = $item->getId();
        }
        $this->assertEquals($expectedIdsRange ? range(...$expectedIdsRange) : [], $ids);
    }

    public function getAllOfPageDataProvider(): array
    {
        return [
            [0, 1, []],
            [1, 1, [1, 1]],
            [7, 1, [1, 2]],
            [7, 2, [3, 4]],
            [7, 3, [5, 6]],
            [7, 4, [7, 7]],
            [7, 5, []],
        ];
    }

    public function testGetIterator(): void
    {
        $getList1 = new GetList($this->makeApiClient($this->makeTransportStub(10, 2)), 1);
        $ids1 = [];
        foreach ($getList1->getIterator() as $item) {
            $ids1[] = $item->getId();
        }
        $this->assertEquals(range(1, 10), $ids1);

        $getList2 = new GetList($this->makeApiClient($this->makeTransportStub(10, 2)), 1);
        $ids2 = [];
        foreach ($getList2 as $item) {
            $ids2[] = $item->getId();
        }
        $this->assertEquals(range(1, 10), $ids2);
    }

    /**
     * @dataProvider getPageCountDataProvider
     */
    public function testGetPageCount(int $totalCount, int $perPage, int $expectedPageCount): void
    {
        $getList = new GetList($this->makeApiClient($this->makeTransportStub($totalCount, $perPage)), 1);
        $this->assertEquals($expectedPageCount, $getList->getPageCount());
    }

    public function getPageCountDataProvider(): array
    {
        return [
            [0, 1, 0],
            [1, 1, 1],
            [7, 1, 7],
            [7, 2, 4],
            [7, 3, 3],
            [7, 4, 2]
        ];
    }

    /**
     * @dataProvider getTotalCountDataProvider
     */
    public function testGetTotalCount(int $totalCount, int $expectedTotalCount): void
    {
        $getList = new GetList($this->makeApiClient($this->makeTransportStub($totalCount)), 1);
        $this->assertEquals($expectedTotalCount, $getList->getTotalCount());
    }

    public function getTotalCountDataProvider(): array
    {
        return [
            [0, 0],
            [1, 1],
            [7, 7]
        ];
    }

    private function makeApiClient(TransportClient $transport): Client
    {
        return new Client(
            ApiVersionEnum::V1,
            new Config(
                'secret',
                new AccessToken('secret', 1668719637, 'Bearer', 'secret_new')
            ),
            $transport
        );
    }

    private function makeTransportStub(int $totalCount, int $perPage = 30): TransportClient
    {
        return new PaginableRequestTransportStub($totalCount, $perPage, static function (int $id): array {
            return [
                'id' => $id,
                'name' => 'donation',
                'username' => 'Tester',
                'message_type' => 'text',
                'message' => 'Test',
                'amount' => 500.7,
                'currency' => 'RUB',
                'is_shown' => 1,
                'created_at' => '2019-09-29 09:00:00',
                'shown_at' => '2019-09-30 09:00:00'
            ];
        });
    }
}
