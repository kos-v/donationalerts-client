<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads\V1\Donations;

use Kosv\DonationalertsClient\API\AbstractPayload;
use Kosv\DonationalertsClient\Validator\ValidationErrors;

final class GetList extends AbstractPayload
{
    public const P_PAGE = 'page';

    /**
     * @param array<self::P_*, mixed> $payload
     */
    public function __construct($payload)
    {
        parent::__construct($payload, self::FORMAT_GET_PARAMS);
    }

    protected function validatePayload($payload): ValidationErrors
    {
        return new ValidationErrors();
    }
}
