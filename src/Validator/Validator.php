<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use OutOfBoundsException;
use function sprintf;

final class Validator
{
    /** @var array<Rule> */
    private array $rules;

    /**
     * @param array<Rule> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param mixed $target
     */
    public function validate($target): ValidationErrors
    {
        $errors = new ValidationErrors();

        foreach ($this->rules as $rule) {
            if ($rule->getKey() === KeysEnum::WHOLE_TARGET) {
                $ruleResult = $rule->check($target);
            } else {
                if (!isset($target[$rule->getKey()])) {
                    throw new OutOfBoundsException(sprintf(
                        'The target does not contain the key "%s"',
                        $rule->getKey()
                    ));
                }
                $ruleResult = $rule->check($target[$rule->getKey()]);
            }
            if (!$ruleResult->isOk()) {
                $errors->addError($rule->getKey(), $ruleResult);
                break;
            }
        }

        return $errors;
    }
}
