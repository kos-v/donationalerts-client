<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\API;

use function array_map;
use function array_merge;
use function hash;
use function implode;
use function is_array;
use function sort;

final class Signer
{
    /** @psalm-readonly */
    private string $clientSecret;

    public function __construct(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function signPayload(AbstractSignablePayload $payload): AbstractSignablePayload
    {
        $payload = clone $payload;

        $payloadValues = array_map(
            static fn ($v) => (string)$v,
            $this->extractPayloadValuesAsList($payload->getFields())
        );
        sort($payloadValues);

        return $payload->setSignature(hash('sha256', implode([
            ...$payloadValues,
            $this->clientSecret,
        ])));
    }

    private function extractPayloadValuesAsList(array $payload): array
    {
        $values = [];
        foreach ($payload as $value) {
            if (is_array($value)) {
                $values = array_merge($values, $this->extractPayloadValuesAsList($value));
            } else {
                $values[] = $value;
            }
        }

        return $values;
    }
}
