<?php

namespace Core\Application\User\Create\Inputs;

use Core\Domain\ValueObjects\EmailValueObject;
use DateTimeInterface;
use SensitiveParameter;

readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public EmailValueObject $email,
        #[SensitiveParameter]
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
