<?php

namespace Core\Domain\Entities\Project\Traits;

use Carbon\CarbonInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Ramsey\Uuid\UuidInterface;

trait ProjectEntityAcessors
{

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
    }

    public function getAccount(): AccountEntity
    {
        return $this->account;
    }

    public function setAccount(AccountEntity $account): void
    {
        $this->account = $account;
    }


    public function getStartAt(): ?CarbonInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?CarbonInterface $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getFinishAt(): ?CarbonInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(?CarbonInterface $finishAt): void
    {
        $this->finishAt = $finishAt;
    }
}
