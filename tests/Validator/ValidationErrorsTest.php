<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\Validator;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Validator\Key;
use Kosv\DonationalertsClient\Validator\RuleCheckResult;
use Kosv\DonationalertsClient\Validator\ValidationErrors;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

final class ValidationErrorsTest extends TestCase
{
    public function testAddErrorWhenCheckResultNotHaveError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The result of checking a rule must contain an error');

        $errors = new ValidationErrors();
        $errors->addError(new RuleCheckResult(new Key('key1'), true));
    }

    public function testGetFirstError(): void
    {
        $errors1 = new ValidationErrors();
        $errors1->addError(new RuleCheckResult(new Key('key1'), false, 'Key1 Error1'));
        $this->assertEquals('Key1 Error1', $errors1->getFirstError());

        $errors2 = new ValidationErrors();
        $errors2->addError(new RuleCheckResult(new Key('key2'), false, 'Key2 Error1'));
        $errors2->addError(new RuleCheckResult(new Key('key1'), false, 'Key1 Error1'));
        $errors2->addError(new RuleCheckResult(new Key('key2'), false, 'Key2 Error2'));
        $this->assertEquals('Key2 Error1', $errors2->getFirstError());
    }

    public function testGetFirstErrorWhenContainerEmpty(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Object not contains errors. Use the isEmpty method before getting an error');

        $errors = new ValidationErrors();
        $errors->getFirstError();
    }

    public function testIsEmpty(): void
    {
        $errors = new ValidationErrors();
        $this->assertTrue($errors->isEmpty());

        $errors->addError(new RuleCheckResult(new Key('key1'), false, 'Key1 Error1'));
        $this->assertFalse($errors->isEmpty());
    }
}
