<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources;

use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use ReflectionClass;
use function sprintf;

abstract class AbstractResource
{
    /** @var array<string,mixed> */
    private array $content;

    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(array $payload)
    {
        $this->prepare($payload);
    }

    /**
     * @return mixed
     */
    final protected function getValue(string $key)
    {
        return $this->content[$key];
    }

    abstract protected function getPayloadContentKey(): string;

    /**
     * @param mixed $payload
     */
    abstract protected function validate($payload): ValidationErrors;

    private function getResourceName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * @param array<string,mixed> $payload
     * @throws ValidateException
     */
    private function prepare(array $payload): void
    {
        if (empty($payload[$this->getPayloadContentKey()])) {
            throw new ValidateException(sprintf(
                'Payload of %s resource is not valid. Error: payload not contains required content key "%s"',
                $this->getResourceName(),
                $this->getPayloadContentKey()
            ));
        }

        $errors = $this->validate($payload[$this->getPayloadContentKey()]);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new ValidateException(sprintf(
                'Payload of %s resource is not valid. Error: "%s":"%s"',
                $this->getResourceName(),
                $firstError->getKey(),
                $firstError->getError()
            ));
        }

        $this->content = $payload[$this->getPayloadContentKey()];
    }
}
