<?php

namespace Core\Domain\Entities\Account\Traits\JoinCode;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

trait AccountJoinCodeAccessors
{
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getAccountId(): ?UuidInterface
    {
        return $this->accountId;
    }

    public function setAccountUuid(?UuidInterface $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}
