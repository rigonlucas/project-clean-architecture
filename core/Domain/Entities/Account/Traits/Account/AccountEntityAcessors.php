<?php

namespace Core\Domain\Entities\Account\Traits\Account;

use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use InvalidArgumentException;

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
        if (empty($name)) {
            throw new InvalidArgumentException('Name is invalid');
        }

        $this->name = $name;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
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
}
