<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use DateTime;

final class IsDatetimeFormatRule extends AbstractRule
{
    private string $format;
    private bool $nullable;

    public function __construct(
        string $key,
        string $format,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->format = $format;
        $this->nullable = $nullable;
        parent::__construct($key, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'The datetime must be specified in the format {{format}}';
    }

    protected function validate($value): string
    {
        if ($this->nullable && $value === null) {
            return '';
        }

        return !DateTime::createFromFormat($this->format, $value) instanceof DateTime
            ? $this->makeErrorMessage(['format' => $this->format])
            : '';
    }
}
