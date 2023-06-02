<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\ValidationErrors;

abstract class AbstractPayload
{
    private array $fields;

    /**
     * @param array<string, mixed> $fields
     */
    public function __construct(array $fields)
    {
        $this->prepare($fields);
    }

    final public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    final protected function setExtraField(string $key, $value)
    {
        $this->fields[$key] = $value;
        return $this;
    }

    abstract protected function validateFields(array $fields): ValidationErrors;

    private function prepare(array $fields): void
    {
        $errors = $this->validateFields($fields);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new InvalidArgumentException(
                "The field {$firstError->getKey()} is not valid. {$firstError->getError()}"
            );
        }
        $this->fields = $fields;
    }
}
