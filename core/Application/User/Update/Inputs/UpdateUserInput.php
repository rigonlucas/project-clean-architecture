<?php

namespace Core\Application\User\Update\Inputs;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

readonly class UpdateUserInput
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public string $email,
        #[SensitiveParameter]
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
