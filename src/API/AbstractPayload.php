<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use InvalidArgumentException;
use function is_array;
use Kosv\DonationalertsClient\Validator\ValidationErrors;

abstract class AbstractPayload
{
    public const FORMAT_GET_PARAMS = 'get_params';
    public const FORMAT_POST_FIELDS = 'post_fields';

    /** @psalm-readonly */
    private string $format;

    /** @var mixed */
    private $payload;

    /**
     * @param mixed $payload
     * @param self::FORMAT_* $format
     */
    public function __construct($payload, string $format)
    {
        $this->format = $format;
        $this->prepare($payload);
    }

    /**
     * @param self::FORMAT_* $format
     */
    final public function isFormat(string $format): bool
    {
        return $format === $this->format && is_array($this->payload);
    }

    /**
     * @return mixed
     */
    final public function toFormat()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    abstract protected function validatePayload($payload): ValidationErrors;

    /**
     * @param mixed $payload
     */
    private function prepare($payload): void
    {
        $errors = $this->validatePayload($payload);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new InvalidArgumentException(
                "The param {$firstError->getKey()} is not valid. {$firstError->getError()}"
            );
        }
        $this->payload = $payload;
    }
}
