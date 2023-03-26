<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations;

use InvalidArgumentException;
use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Client;
use Kosv\DonationalertsClient\API\Enums\PaginationEnum;

final class Donations extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param positive-int $page
     */
    public function getList(int $page = PaginationEnum::PERPAGE_FIRST_PAGE): GetList
    {
        if ($page < 1) {
            throw new InvalidArgumentException('The value of the $page argument must be positive');
        }

        return new GetList($this->client, $page);
    }
}
