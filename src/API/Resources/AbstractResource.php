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
     * @param array<mixed> $content
     */
    public function __construct(array $content)
    {
        $this->prepare($content);
    }

    /**
     * @param int|string $key
     * @return mixed
     */
    final protected function getContentValue($key)
    {
        return $this->content[$key];
    }

    /**
     * @param mixed $content
     */
    abstract protected function validateContent($content): ValidationErrors;

    private function getResourceName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * @param array<mixed> $content
     * @throws ValidateException
     */
    private function prepare(array $content): void
    {
        $errors = $this->validateContent($content);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new ValidateException(sprintf(
                'Content of %s resource is not valid. Error: "%s":"%s"',
                $this->getResourceName(),
                $firstError->getKey(),
                $firstError->getError()
            ));
        }

        $this->content = $content;
    }
}
