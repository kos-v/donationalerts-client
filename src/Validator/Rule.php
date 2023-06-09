<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Validator;

interface Rule
{
    /**
     * @param mixed $value
     */
    public function check($value): RuleCheckResult;

    public function getKey(): Key;
}
