<?php

namespace Core\Domain\Entities\Account\Traits\JoinCode;

use DateTimeInterface;

trait AccountJoinCodeAccessors
{
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(?int $accountId): void
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
