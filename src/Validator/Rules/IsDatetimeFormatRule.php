<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use DateTime;
use function is_string;

final class IsDatetimeFormatRule extends AbstractRule
{
    private string $format;

    public function __construct(
        string $key,
        string $format,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->format = $format;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The datetime must be specified in the format {{format}}';
    }

    protected function validate($value): string
    {
        return !is_string($value) || !DateTime::createFromFormat($this->format, $value) instanceof DateTime
            ? $this->makeErrorMessage(['format' => $this->format])
            : '';
    }
}
