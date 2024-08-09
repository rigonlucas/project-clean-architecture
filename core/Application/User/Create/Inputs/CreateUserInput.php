<?php

namespace Core\Application\User\Create\Inputs;

use DateTimeInterface;
use SensitiveParameter;

readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public string $email,
        #[SensitiveParameter]
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
