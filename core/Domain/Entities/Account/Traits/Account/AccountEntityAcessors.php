<?php

namespace Core\Domain\Entities\Account\Traits\Account;

use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use Core\Domain\Entities\User\UserEntity;
use Ramsey\Uuid\UuidInterface;

trait AccountEntityAcessors
{
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getJoinCodeEntity(): ?AccountJoinCodeEntity
    {
        return $this->joinCodeEntity;
    }

    public function setJoinCodeEntity(AccountJoinCodeEntity $joinCodeEntity): self
    {
        $this->joinCodeEntity = $joinCodeEntity;
        return $this;
    }

    public function getUserEntity(): UserEntity
    {
        return $this->userEntity;
    }

    public function setUserEntity(UserEntity $userEntity): self
    {
        $this->userEntity = $userEntity;
        return $this;
    }
}
