<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

use function array_key_exists;
use OutOfBoundsException;
use function sprintf;

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
                if (!array_key_exists($rule->getKey(), $target)) {
                    throw new OutOfBoundsException(sprintf(
                        'The target does not contain the key "%s"',
                        $rule->getKey()
                    ));
                }
                $ruleResult = $rule->check($target[$rule->getKey()]);
            }
            if (!$ruleResult->isOk()) {
                $errors->addError($ruleResult);
                break;
            }
        }

        return $errors;
    }
}
