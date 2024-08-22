<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\InvalidEmailException;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

trait UserEntityAcessors
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): EmailValueObject
    {
        return $this->email;
    }

    /**
     * @throws InvalidEmailException
     */
    public function setEmail(EmailValueObject $email): self
    {
        $this->email = new EmailValueObject($email, false);
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

    public function setName(string $name): self
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

    public function getAccount(): ?AccountEntity
    {
        return $this->account;
    }

    public function setAccount(?AccountEntity $account): self
    {
        $this->account = $account;
        return $this;
    }
}