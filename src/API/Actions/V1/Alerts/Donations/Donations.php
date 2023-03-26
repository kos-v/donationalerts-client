<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Actions\V1\Alerts\Donations;

use InvalidArgumentException;
use Kosv\DonationalertsClient\API\AbstractAction;
use Kosv\DonationalertsClient\API\Enums\PaginationEnum;

final class Donations extends AbstractAction
{
    /**
     * @param positive-int $page
     */
    public function getList(int $page = PaginationEnum::PERPAGE_FIRST_PAGE): GetList
    {
        if ($page < 1) {
            throw new InvalidArgumentException('The value of the $page argument must be positive');
        }

        return new GetList($this->getApiClient(), $page);
    }
}
