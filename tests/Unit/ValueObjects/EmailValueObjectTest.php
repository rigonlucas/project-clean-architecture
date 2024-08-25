<?php

namespace Tests\Unit\ValueObjects;

use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\InvalidEmailException;
use Core\Support\Http\ResponseStatus;
use Tests\TestCase;

/**
 * @group email_value_objects
 */
class EmailValueObjectTest extends TestCase
{
    public function test_valid_email()
    {
        $email = new EmailValueObject('test@example.com');
        $this->assertEquals('test@example.com', $email);
    }

    public function test_invalid_email()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        new EmailValueObject('invalid-email');
    }

    public function test_suppressed_email()
    {
        $email = new EmailValueObject('test@example.com');
        $this->assertEquals('te**@ex*********', $email->supress());
    }

    public function test_unsuppressed_email()
    {
        $email = new EmailValueObject('test@example.com');
        $this->assertEquals('te**@ex*********', $email->supress());
        $this->assertEquals('test@example.com', $email->unsupress());
    }

    public function test_is_email_suppressed()
    {
        $this->assertTrue(EmailValueObject::isEmailSuppressed('te**@ex*********'));
        $this->assertFalse(EmailValueObject::isEmailSuppressed('email@emailc.omc'));
    }

    public function test_is_valid_email()
    {
        $this->assertTrue((new EmailValueObject('email@email.com', false))->isValid());
        $this->assertFalse((new EmailValueObject('invalid-email', false))->isValid());
    }

    public function test_is_invalid_email()
    {
        $this->assertFalse((new EmailValueObject('email@email.com', false))->isInvalid());
        $this->assertTrue((new EmailValueObject('invalid-email', false))->isInvalid());
    }
}
