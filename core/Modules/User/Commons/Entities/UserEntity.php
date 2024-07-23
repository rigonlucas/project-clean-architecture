<?php

namespace Core\Modules\User\Commons\Entities;

use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use DateTimeInterface;

class UserEntity
{
    private ?int $id = null;
    private string $name;
    private ?string $email;
    private ?string $password;
    private ?DateTimeInterface $birthday;

    private function __construct()
    {
    }

    public static function create(
        string $name,
        string $email,
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;
        $userEntity->birthday = $birthday;

        return $userEntity;
    }

    public static function update(
        int $id,
        string $name,
        string $email,
        ?string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;
        $userEntity->birthday = $birthday;

        return $userEntity;
    }

    public static function details(
        int $id,
        string $name,
        string $email,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->birthday = $birthday;

        return $userEntity;
    }

    /**
     * @throws InvalidAgeException
     */
    public function validateAge(): void
    {
        if ($this->birthday < 18) {
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

    public function getBirthday(): ?int
    {
        return $this->birthday;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
