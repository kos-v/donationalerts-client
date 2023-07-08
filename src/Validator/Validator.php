<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function array_key_exists;
use function is_array;
use Kosv\DonationalertsClient\Validator\Exceptions\KeyPartNotFoundException;

final class Validator
{
    /**
     * @var array<Rule>
     * @psalm-readonly
     */
    private array $rules;

    /**
     * @param array<Rule> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function validate(array $target): ValidationErrors
    {
        $errors = new ValidationErrors();

        foreach ($this->rules as $rule) {
            $ruleResult = null;

            if ((string)$rule->getKey() === KeysEnum::WHOLE_TARGET) {
                $ruleResult = $rule->check($target);
            } else {
                try {
                    $targetPart = $this->extractTargetPartByKey($target, $rule->getKey());
                } catch (KeyPartNotFoundException $e) {
                    continue;
                }

                if ($rule->getKey()->getLastPart() === KeysEnum::ALL_IN_LIST) {
                    if (!is_array($targetPart)) {
                        continue;
                    }

                    /** @var mixed $targetPartItem */
                    foreach ($targetPart as $targetPartItem) {
                        $ruleResult = $rule->check($targetPartItem);
                        if (!$ruleResult->isOk()) {
                            break;
                        }
                    }
                } else {
                    $ruleResult = $rule->check($targetPart);
                }
            }

            if ($ruleResult && !$ruleResult->isOk()) {
                $errors->addError($ruleResult);
                break;
            }
        }

        return $errors;
    }

    /**
     * @return mixed
     * @throws KeyPartNotFoundException
     */
    private function extractTargetPartByKey(array $target, Key $key)
    {
        $targetPart = $target;
        foreach ($key->toParts() as $keyPart) {
            if ($keyPart === KeysEnum::ALL_IN_LIST) {
                break;
            }

            if (!is_array($targetPart) || !array_key_exists($keyPart, $targetPart)) {
                throw new KeyPartNotFoundException("Part '{$keyPart}' of key '{$key}' not found");
            }

            /** @var mixed $targetPart */
            $targetPart = $targetPart[$keyPart];
        }

        return $targetPart;
    }
}
