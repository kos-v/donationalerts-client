<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API\Resources;

use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use ReflectionClass;
use function sprintf;

abstract class AbstractResource
{
    /** @var array<mixed> */
    private array $content;

    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(array $payload)
    {
        $this->prepare($payload);
    }

    final protected function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    final protected function getValue(string $key)
    {
        return $this->getContent()[$key];
    }

    abstract protected function getPayloadContentKey(): string;

    /**
     * @param mixed $content
     */
    abstract protected function validate($content): ValidationErrors;

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
        $contentKey = $this->getPayloadContentKey();
        if ($contentKey && empty($payload[$contentKey])) {
            throw new ValidateException(sprintf(
                'Payload of %s resource is not valid. Error: payload not contains required content key "%s"',
                $this->getResourceName(),
                $this->getPayloadContentKey()
            ));
        }

        $content = $contentKey ? $payload[$contentKey] : $payload;

        $errors = $this->validate($content);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new ValidateException(sprintf(
                'Payload of %s resource is not valid. Error: "%s":"%s"',
                $this->getResourceName(),
                $firstError->getKey(),
                $firstError->getError()
            ));
        }

        $this->content = $content;
    }
}
