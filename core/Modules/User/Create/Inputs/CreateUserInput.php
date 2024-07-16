<?php

namespace Core\Modules\User\Create\Inputs;

readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $age
    ) {
    }
}
