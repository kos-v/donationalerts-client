<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API;

use Kosv\DonationalertsClient\API\AbstractSignablePayload;
use Kosv\DonationalertsClient\API\Signer;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use PHPUnit\Framework\TestCase;

final class SignerTest extends TestCase
{
    private const PAYLOAD_SIGNATURE_FIELD_KEY = 'signature';

    /**
     * @dataProvider getSignPayloadDataProvider
     */
    public function testSignPayload(string $secret, AbstractSignablePayload $payload, string $expectedSignature): void
    {
        $signer = new Signer($secret);
        $this->assertEquals(
            $expectedSignature,
            $signer->signPayload($payload)->getFields()[self::PAYLOAD_SIGNATURE_FIELD_KEY]
        );
    }

    public function getSignPayloadDataProvider(): iterable
    {
        return [
            [
                '',
                $this->makeSignablePayload([]),
                'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'
            ],
            [
                'secret',
                $this->makeSignablePayload([]),
                '2bb80d537b1da3e38bd30361aa855686bde0eacd7162fef6a25fe97bf527a25b'
            ],
            [
                'secret',
                $this->makeSignablePayload([
                    'a' => null,
                    'b' => -1,
                    'c' => 0,
                    'd' => 1,
                    'e' => '',
                    'f' => 'string',
                    'g' => [],
                    'h' => [3, 'String', null],
                    'i' => [
                        'a' => 1234567890,
                        'b' => 'strInG'
                    ],
                    'j' => '123string'
                ]),
                '2de86dbe790a9d36cbdd327ef6de898d722def50cbcad57cb9d463b21b360890'
            ]
        ];
    }

    private function makeSignablePayload(array $fields): AbstractSignablePayload
    {
        return new class ($fields, self::PAYLOAD_SIGNATURE_FIELD_KEY) extends AbstractSignablePayload {
            private string $signatureFieldKey;

            public function __construct(array $fields, string $signatureFieldKey)
            {
                $this->signatureFieldKey = $signatureFieldKey;
                parent::__construct($fields);
            }

            public function getSignatureFieldKey(): string
            {
                return $this->signatureFieldKey;
            }

            protected function validateFields(array $fields): ValidationErrors
            {
                return new ValidationErrors();
            }
        };
    }
}
