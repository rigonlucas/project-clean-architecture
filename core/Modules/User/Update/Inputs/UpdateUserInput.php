<?php

namespace Core\Modules\User\Update\Inputs;

readonly class UpdateUserInput
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public int $age
    ) {
    }
}
