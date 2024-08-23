<?php

namespace Core\Application\User\Update\Inputs;

use Core\Domain\ValueObjects\EmailValueObject;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

readonly class UpdateUserInput
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public EmailValueObject $email,
        #[SensitiveParameter]
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
