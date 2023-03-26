<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Alerts\Donations;

use BadMethodCallException;
use Kosv\DonationalertsClient\API\Resources\V1\Alerts\Donations\GetListCollection;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

final class GetListCollectionTest extends TestCase
{
    public function test__constructWhenContentIsNotListArray(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of GetListCollection resource is not valid. Error: "[*]":"The value must be listable array type"');

        new GetListCollection($this->makeItem());
    }

    /**
     * @dataProvider countDataProvider
     */
    public function testCount(array $items, int $expected): void
    {
        $collection = new GetListCollection($items);
        $this->assertCount($expected, $collection);
    }

    public function countDataProvider(): array
    {
        return [
            [[], 0],
            [[$this->makeItem()], 1],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], 3],
        ];
    }

    /**
     * @dataProvider offsetExistsDataProvider
     */
    public function testOffsetExists(array $items, int $index, bool $expected): void
    {
        $collection = new GetListCollection($items);
        $this->assertEquals($expected, isset($collection[$index]));
    }

    public function offsetExistsDataProvider(): array
    {
        return [
            [[$this->makeItem()], 0, true],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], 1, true],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], 2, true],
            [[], 0, false],
            [[$this->makeItem()], 1, false],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], 3, false],
        ];
    }

    /**
     * @dataProvider offsetGetDataProvider
     */
    public function testOffsetGet(array $items, int $index, int $expectedId): void
    {
        $collection = new GetListCollection($items);
        $this->assertEquals($expectedId, $collection[$index]->getId());
    }

    public function offsetGetDataProvider(): array
    {
        return [
            [[$this->makeItem(1)], 0, 1],
            [[$this->makeItem(1), $this->makeItem(2), $this->makeItem(3)], 1, 2],
            [[$this->makeItem(1), $this->makeItem(2), $this->makeItem(3)], 2, 3],
        ];
    }

    /**
     * @dataProvider offsetGetWhenOutOfRangeDataProvider
     */
    public function testOffsetGetWhenOutOfRange(array $items, int $index): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage("Index {$index} does not exist");

        $collection = new GetListCollection($items);
        $collection[$index];
    }

    public function offsetGetWhenOutOfRangeDataProvider(): array
    {
        return [
            [[], -1],
            [[], 0],
            [[], 1],
            [[$this->makeItem()], -1],
            [[$this->makeItem()], 1],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], -1],
            [[$this->makeItem(), $this->makeItem(), $this->makeItem()], 3],
        ];
    }

    public function testOffsetSet(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The collection is read-only');

        $collection = new GetListCollection([$this->makeItem()]);
        $collection[0] = $this->makeItem();
    }

    public function testOffsetUnset(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The collection is read-only');

        $collection = new GetListCollection([$this->makeItem()]);
        unset($collection[0]);
    }

    private function makeItem(int $id = 1): array
    {
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
    }
}
