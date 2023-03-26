<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API\Actions\V1\Alerts\Donations;

use InvalidArgumentException;
use Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations\GetListIterator;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Config;
use Kosv\DonationalertsClient\API\Enums\ApiVersionEnum;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Tests\__TestTools\PaginableRequestTransportStub;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use PHPUnit\Framework\TestCase;
use function range;

final class GetListIteratorTest extends TestCase
{
    /**
     * @dataProvider constructWhenStartPageNumberNotPositiveDataProvider
     */
    public function test__constructWhenStartPageNumberNotPositive(int $startPage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value of the $startPage argument must be positive');

        new GetListIterator(
            $this->makeApiClient($this->createMock(TransportClient::class)),
            $startPage
        );
    }

    public function constructWhenStartPageNumberNotPositiveDataProvider(): array
    {
        return [[-1], [0]];
    }

    /**
     * @dataProvider iterateDataProvider
     */
    public function testIterate(
        int $totalCount,
        int $perPage,
        int $startPage,
        bool $onlyStartPage,
        array $expectedIdsRange
    ): void {
        $iterator = new GetListIterator(
            $this->makeApiClient($this->makeTransportStub($totalCount, $perPage)),
            $startPage,
            $onlyStartPage
        );
        $ids = [];
        foreach ($iterator as $key => $item) {
            $ids[] = $item->getId();
        }

        $expectedIds = $expectedIdsRange ? range(...$expectedIdsRange) : [];
        $this->assertEquals($expectedIds, $ids);
    }

    public function iterateDataProvider(): array
    {
        return [
            [0, 0, 1, false, []],
            [0, 1, 1, false, []],
            [0, 1, 2, false, []],
            [0, 2, 1, false, []],
            [0, 2, 2, false, []],
            [1, 1, 1, false, [1, 1]],
            [1, 1, 2, false, []],
            [1, 2, 1, false, [1, 1]],
            [1, 2, 2, false, []],
            [7, 1, 1, false, [1, 7]],
            [7, 1, 2, false, [2, 7]],
            [7, 1, 3, false, [3, 7]],
            [7, 1, 7, false, [7, 7]],
            [7, 1, 8, false, []],
            [7, 2, 1, false, [1, 7]],
            [7, 2, 2, false, [3, 7]],
            [7, 2, 3, false, [5, 7]],
            [7, 2, 4, false, [7, 7]],
            [7, 2, 5, false, []],
            [7, 3, 1, false, [1, 7]],
            [7, 3, 2, false, [4, 7]],
            [7, 3, 3, false, [7, 7]],
            [7, 3, 4, false, []],
            [0, 0, 1, true, []],
            [0, 1, 1, true, []],
            [0, 2, 1, true, []],
            [1, 1, 1, true, [1, 1]],
            [1, 1, 2, true, []],
            [1, 2, 1, true, [1, 1]],
            [1, 2, 2, true, []],
            [7, 1, 1, true, [1, 1]],
            [7, 1, 2, true, [2, 2]],
            [7, 1, 3, true, [3, 3]],
            [7, 1, 7, true, [7, 7]],
            [7, 1, 8, true, []],
            [7, 2, 1, true, [1, 2]],
            [7, 2, 2, true, [3, 4]],
            [7, 2, 3, true, [5, 6]],
            [7, 2, 4, true, [7, 7]],
            [7, 2, 5, true, []],
            [7, 3, 1, true, [1, 3]],
            [7, 3, 2, true, [4, 6]],
            [7, 3, 3, true, [7, 7]],
            [7, 3, 4, true, []],
        ];
    }

    private function makeApiClient(TransportClient $transport): Client
    {
        return new Client(
            ApiVersionEnum::V1,
            new Config(new AccessToken('secret', 1668719637, 'Bearer', 'secret_new')),
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
