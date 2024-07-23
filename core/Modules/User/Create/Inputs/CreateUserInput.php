<?php

namespace Core\Modules\User\Create\Inputs;

use DateTimeInterface;

readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
