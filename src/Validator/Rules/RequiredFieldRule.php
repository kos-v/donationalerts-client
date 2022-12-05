<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator\Rules;

use function count;
use InvalidArgumentException;
use function is_array;

final class RequiredFieldRule extends AbstractRule
{
    /** @var array<string> */
    private array $requiredFields;

    public function __construct(
        string $key,
        array $requiredFields,
        bool $nullable = false,
        ?string $errMsg = self::ERR_MSG_DEFAULT
    ) {
        $this->requiredFields = $requiredFields;
        parent::__construct($key, $nullable, $errMsg);
    }

    protected function getDefaultError(): string
    {
        return 'Required fields [{{notFoundFields}}] are not set';
    }

    protected function validate($value): string
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Argument $value must be contains an array');
        }

        $notFoundFields = [];
        foreach ($this->requiredFields as $requiredField) {
            if (empty($value[$requiredField])) {
                $notFoundFields[] = $requiredField;
            }
        }

        return count($notFoundFields)
            ? $this->makeErrorMessage(['notFoundFields' => $notFoundFields])
            : '';
    }
}
