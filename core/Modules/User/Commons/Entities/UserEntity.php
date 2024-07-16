<?php

namespace Core\Modules\User\Commons\Entities;

use Core\Modules\User\Create\Exceptions\InvalidAgeException;

class UserEntity
{
    /**
     * @throws InvalidAgeException
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $age
    ) {
        $this->validateAge();
    }

    public function validateAge(): void
    {
        if ($this->age < 18) {
            throw new InvalidAgeException('Idade inválida');
        }
    }
}
