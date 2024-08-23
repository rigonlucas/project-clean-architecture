<?php

namespace Tests\Unit\ValueObjects;

use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\InvalidEmailException;
use Core\Support\Http\ResponseStatus;
use Tests\TestCase;

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
}
