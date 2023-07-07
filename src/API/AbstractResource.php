<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use function array_key_exists;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use ReflectionClass;
use function sprintf;

abstract class AbstractResource
{
    /** @var array<mixed> */
    private array $content;

    /**
     * @param array<mixed> $content
     */
    public function __construct(array $content)
    {
        $this->prepare($content);
    }

    /**
     * @return array<mixed>
     */
    final protected function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param int|string $key
     * @param mixed $default
     * @return mixed
     */
    final protected function getContentValue($key, $default = null)
    {
        return array_key_exists($key, $this->content) ? $this->content[$key] : $default;
    }

    abstract protected function validateContent(array $content): ValidationErrors;

    private function getResourceName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * @param array<mixed> $content
     * @return array<mixed>
     */
    protected function prepareContentBeforeValidate(array $content): array
    {
        return $content;
    }

    /**
     * @param array<mixed> $content
     * @throws ValidateException
     */
    private function prepare(array $content): void
    {
        $content = $this->prepareContentBeforeValidate($content);

        $errors = $this->validateContent($content);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new ValidateException(sprintf(
                'Content of %s resource is not valid. Error: "%s":"%s"',
                $this->getResourceName(),
                (string)$firstError->getKey(),
                $firstError->getError()
            ));
        }

        $this->content = $content;
    }
}
