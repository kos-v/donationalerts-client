<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator;

use Kosv\DonationalertsClient\Validator\Key;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rule;
use Kosv\DonationalertsClient\Validator\RuleCheckResult;
use Kosv\DonationalertsClient\Validator\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $target, array $rules, bool $isEmpty): void
    {
        $validator = new Validator($rules);
        $this->assertEquals($isEmpty, $validator->validate($target)->isEmpty());
    }

    public function validateDataProvider(): array
    {
        return [
            [
                [],
                [],
                true
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [],
                true
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [
                    $this->makeRuleMock('key1', true),
                    $this->makeRuleMock('key2', true)
                ],
                true
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [
                    $this->makeRuleMock(KeysEnum::WHOLE_TARGET, true),
                    $this->makeRuleMock('key2', true)
                ],
                true
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [
                    $this->makeRuleMock('key1', false)
                ],
                false
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [
                    $this->makeRuleMock('key1', true),
                    $this->makeRuleMock('key2', false)
                ],
                false
            ],
            [
                ['key1' => 1, 'key2' => 2],
                [
                    $this->makeRuleMock(KeysEnum::WHOLE_TARGET, false),
                    $this->makeRuleMock('key2', true)
                ],
                false
            ],
            [
                ['key1' => ['key2' => 2]],
                [
                    $this->makeRuleMock('key1.key2', true),
                ],
                true
            ],
            [
                ['key1' => ['key2' => 2]],
                [
                    $this->makeRuleMock('key1.key2', false),
                ],
                false
            ],
            [
                [
                    'key1' => 1,
                    'key2' => ['key3' => 3]
                ],
                [
                    $this->makeRuleMock('key1', true),
                    $this->makeRuleMock('key2.key3', false),
                ],
                false
            ],
            [
                [
                    'key1' => ['key2' => []]
                ],
                [
                    $this->makeRuleMock('key1.*', true),
                ],
                true
            ],
            [
                [
                    'key1' => ['key2' => ['key3' => 3]]
                ],
                [
                    $this->makeRuleMock('key1.*', true),
                ],
                true
            ],
            [
                [
                    'key1' => ['key2' => ['key3' => 3]],
                    'key4' => 4,
                ],
                [
                    $this->makeRuleMock('key1.*', true),
                    $this->makeRuleMock('key4', true),
                ],
                true
            ],
            [
                [
                    'key1' => ['key2' => ['key3' => 3]],
                    'key4' => 4,
                ],
                [
                    $this->makeRuleMock('key1.*', false),
                    $this->makeRuleMock('key4', true),
                ],
                false
            ],
            [
                [
                    'key1' => ['key2' => ['key3' => 3]],
                    'key4' => 4,
                ],
                [
                    $this->makeRuleMock('key1.*', true),
                    $this->makeRuleMock('key4', false),
                ],
                false
            ],
        ];
    }

    private function makeRuleMock(string $key, bool $ok): Rule
    {
        $rule = $this->createMock(Rule::class);
        $rule->method('getKey')->willReturn(new Key($key));
        $rule->method('check')->willReturn(new RuleCheckResult(new Key($key), $ok));

        return $rule;
    }
}
