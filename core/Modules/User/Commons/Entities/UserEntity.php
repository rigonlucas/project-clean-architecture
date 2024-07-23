<?php

namespace Core\Modules\User\Commons\Entities;

use Core\Modules\User\Commons\Entities\Traits\HasUserEntityBuilder;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use DateTime;
use DateTimeInterface;

class UserEntity
{
    use HasUserEntityBuilder;

    private ?int $id = null;
    private string $name;
    private ?string $email;
    private ?string $password;
    private ?DateTimeInterface $birthday;

    private function __construct()
    {
    }

    /**
     * @throws InvalidAgeException
     */
    public function validateAge(): void
    {
        if ($this->birthday->diff(new DateTime())->y < 18) {
            throw new InvalidAgeException('Idade inválida');
        }
    }

    public function validateAgeLower(): void
    {
        if ($this->birthday->diff(new DateTime())->y < 18) {
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

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
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

    public function setNome(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
