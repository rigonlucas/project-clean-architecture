<?php

namespace Core\Modules\User\Commons\Entities;

use Core\Modules\User\Commons\Exceptions\InvalidAgeException;

class UserEntity
{
    private ?int $id = null;

    /**
     * @throws InvalidAgeException
     */
    public function __construct(
        private string $name,
        private string $email,
        private ?string $password = null,
        private ?int $age = null
    ) {
        $this->validateAge();
    }

    /**
     * @throws InvalidAgeException
     */
    public function validateAge(): void
    {
        if ($this->age < 18) {
            throw new InvalidAgeException('Idade inválida');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setNome(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
