<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsKeyableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\IsListableArrayRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\Validator;
use function sprintf;

final class RawResourceExtractor
{
    private const DEFAULT_CONTENT_KEY = 'data';
    private const DEFAULT_METADATA_KEY = 'meta';

    /**
     * @var array<string,mixed>
     * @psalm-readonly
     */
    private array $payload;

    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @throws ValidateException
     */
    public function extractContent(string $key = self::DEFAULT_CONTENT_KEY, bool $keyable = true): array
    {
        return $this->extract($key, [
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [$key]),
            $keyable ? new IsKeyableArrayRule($key) : new IsListableArrayRule($key),
        ]);
    }

    /**
     * @throws ValidateException
     */
    public function extractMetadata(string $key = self::DEFAULT_METADATA_KEY): array
    {
        return $this->extract($key, [
            new RequiredFieldRule(KeysEnum::WHOLE_TARGET, [$key]),
            new IsKeyableArrayRule($key),
        ]);
    }

    /**
     * @throws ValidateException
     */
    private function extract(string $key, array $rules): array
    {
        $errors = (new Validator($rules))->validate($this->payload);
        if (!$errors->isEmpty()) {
            $firstError = $errors->getFirstError();
            throw new ValidateException(sprintf(
                'Payload is not valid. Error: "%s":"%s"',
                $firstError->getKey(),
                $firstError->getError()
            ));
        }

        return $this->payload[$key];
    }
}
