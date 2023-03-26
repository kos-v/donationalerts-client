<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Alerts\Donations;

use Kosv\DonationalertsClient\API\Resources\V1\Alerts\Donations\GetListItem;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class GetListItemTest extends TestCase
{
    public function testGetValues(): void
    {
        $listItem1 = new GetListItem([
            'id' => 30530030,
            'name' => 'donation',
            'username' => 'Tester',
            'message_type' => 'text',
            'message' => 'Test',
            'amount' => 500.7,
            'currency' => 'RUB',
            'is_shown' => 1,
            'created_at' => '2019-09-29 09:00:00',
            'shown_at' => '2019-09-30 09:00:00'
        ]);

        $this->assertEquals(30530030, $listItem1->getId());
        $this->assertEquals('donation', $listItem1->getName());
        $this->assertEquals('Tester', $listItem1->getUsername());
        $this->assertEquals('text', $listItem1->getMessageType());
        $this->assertEquals('Test', $listItem1->getMessage());
        $this->assertEquals(500.7, $listItem1->getAmount());
        $this->assertEquals('RUB', $listItem1->getCurrency());
        $this->assertEquals(1, $listItem1->getIsShown());
        $this->assertEquals('2019-09-29 09:00:00', $listItem1->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals('2019-09-30 09:00:00', $listItem1->getShownAt()->format('Y-m-d H:i:s'));

        $listItem2 = new GetListItem([
            'id' => 30530030,
            'name' => 'donation',
            'username' => 'Tester',
            'message_type' => 'text',
            'message' => 'Test',
            'amount' => 500.7,
            'currency' => 'RUB',
            'is_shown' => 1,
            'created_at' => '2019-09-29 09:00:00',
            'shown_at' => null
        ]);

        $this->assertNull($listItem2->getShownAt());
    }

    public function testUnexpectedContentFormat(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of GetListItem resource is not valid. Error: "[*]":"The value must be keyable array type"');

        new GetListItem([
            [
                'id' => 30530030,
                'name' => 'donation',
                'username' => 'Tester',
                'message_type' => 'text',
                'message' => 'Test',
                'amount' => 500.7,
                'currency' => 'RUB',
                'is_shown' => 1,
                'created_at' => '2019-09-29 09:00:00',
                'shown_at' => '2019-09-30 09:00:00'
            ],
        ]);
    }

    public function testWithoutRequiredFields(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of GetListItem resource is not valid. Error: "[*]":"Required fields [id, name, username, message_type, message, amount, currency, is_shown, created_at, shown_at] are not set"');

        new GetListItem([]);
    }

    public function testWithUnexpectedFieldType(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Content of GetListItem resource is not valid. Error: "id":"The value does not match the integer type"');

        new GetListItem([
            'id' => 'id',
            'name' => 'donation',
            'username' => 'Tester',
            'message_type' => 'text',
            'message' => 'Test',
            'amount' => 500.7,
            'currency' => 'RUB',
            'is_shown' => 1,
            'created_at' => '2019-09-29 09:00:00',
            'shown_at' => '2019-09-30 09:00:00'
        ]);
    }
}
