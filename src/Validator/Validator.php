<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function array_key_exists;

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
            if ((string)$rule->getKey() === KeysEnum::WHOLE_TARGET) {
                $ruleResult = $rule->check($target);
            } else {
                if (!array_key_exists((string)$rule->getKey(), $target)) {
                    continue;
                }
                $ruleResult = $rule->check($target[(string)$rule->getKey()]);
            }
            if (!$ruleResult->isOk()) {
                $errors->addError($ruleResult);
                break;
            }
        }

        return $errors;
    }
}
