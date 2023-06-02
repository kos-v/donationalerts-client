<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads\V1\Alerts\Donations;

use Kosv\DonationalertsClient\API\AbstractPayload;
use Kosv\DonationalertsClient\Validator\ValidationErrors;

final class GetList extends AbstractPayload
{
    public const F_PAGE = 'page';

    /**
     * @param array<self::F_*, mixed> $payload
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);
    }

    protected function validateFields(array $fields): ValidationErrors
    {
        return new ValidationErrors();
    }
}
