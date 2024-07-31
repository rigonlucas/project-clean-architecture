<?php

namespace Core\Modules\User\Commons\Entities;

use AllowDynamicProperties;
use Core\Modules\User\Commons\Entities\Traits\HasUserEntityBuilder;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

#[AllowDynamicProperties] class UserEntity
{
    use HasUserEntityBuilder;

    private ?int $id = null;
    private string $name;
    private ?string $email;
    private ?string $password;
    private UuidInterface $uuid;
    private ?DateTimeInterface $birthday;

    private function __construct()
    {
    }

    public function hasNoLegalAge(): bool
    {
        return !($this->birthday->diff(new DateTime())->y >= 18);
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

    public function setPassword(
        #[SensitiveParameter]
        string $password
    ): self {
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

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }
}
