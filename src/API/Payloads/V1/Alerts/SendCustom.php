<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Payloads\V1\Alerts;

use Kosv\DonationalertsClient\API\AbstractPayload;
use Kosv\DonationalertsClient\Validator\Rules\InRule;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\StringLenRule;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use Kosv\DonationalertsClient\Validator\Validator;

final class SendCustom extends AbstractPayload
{
    public const F_EXTERNAL_ID = 'external_id';
    public const F_HEADER = 'header';
    public const F_IS_SHOWN = 'is_shown';
    public const F_IMAGE_URL = 'image_url';
    public const F_MESSAGE = 'message';
    public const F_SOUND_URL = 'sound_url';

    /**
     * @param array<self::F_*, mixed> $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);
    }

    protected function validateFields(array $fields): ValidationErrors
    {
        return (new Validator([
            new IsTypeRule(self::F_EXTERNAL_ID, 'string'),
            new IsTypeRule(self::F_HEADER, 'string'),
            new IsTypeRule(self::F_MESSAGE, 'string'),
            new IsTypeRule(self::F_IS_SHOWN, 'integer'),
            new IsTypeRule(self::F_IMAGE_URL, 'string'),
            new IsTypeRule(self::F_SOUND_URL, 'string'),
            new InRule(self::F_IS_SHOWN, [0, 1]),
            new StringLenRule(self::F_EXTERNAL_ID, 0, 32),
            new StringLenRule(self::F_HEADER, 0, 255),
            new StringLenRule(self::F_MESSAGE, 0, 300),
            new StringLenRule(self::F_IMAGE_URL, 0, 255),
            new StringLenRule(self::F_SOUND_URL, 0, 255),
        ]))->validate($fields);
    }
}
