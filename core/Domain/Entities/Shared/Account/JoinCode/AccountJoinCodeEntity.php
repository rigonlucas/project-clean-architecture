<?php

namespace Core\Domain\Entities\Shared\Account\JoinCode;

use Core\Application\Account\Commons\Exceptions\AccountJoinCodeInvalidException;
use Core\Domain\Entities\Shared\Account\Traits\JoinCode\AccountJoinCodeAccessors;
use Core\Support\Http\ResponseStatus;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class AccountJoinCodeEntity
{
    use AccountJoinCodeBuilder;

    private ?UuidInterface $uuid = null;
    private ?string $code = null;
    private ?UuidInterface $accountId = null;
    private ?DateTimeInterface $expiresAt = null;

    private function __construct()
    {
    }

    public function validateJoinCode(): void
    {
        if ($this->expiresAt && $this->expiresAt < new DateTime()) {
            throw new AccountJoinCodeInvalidException(
                'Join code has expired',
                ResponseStatus::BAD_REQUEST->value
            );
        }

        if (strlen($this->code) !== 6) {
            throw new AccountJoinCodeInvalidException(
                'Join code must be 6 characters long',
                ResponseStatus::BAD_REQUEST->value
            );
        }
    }

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
