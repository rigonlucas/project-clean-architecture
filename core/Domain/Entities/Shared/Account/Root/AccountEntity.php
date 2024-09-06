<?php

namespace Core\Domain\Entities\Shared\Account\Root;

use Core\Application\Account\Shared\Exceptions\AccountNameInvalidException;
use Core\Domain\Entities\Shared\Account\JoinCode\AccountJoinCodeEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Http\ResponseStatus;
use Ramsey\Uuid\UuidInterface;

class AccountEntity
{
    use AccountEntityBuilder;

    private ?string $name = null;
    private ?UuidInterface $uuid = null;
    private ?AccountJoinCodeEntity $joinCodeEntity = null;

    private UserEntity $userEntity;

    private function __construct()
    {
    }

    /**
     * @throws AccountNameInvalidException
     */
    public function validateAccountName(): void
    {
        if (is_null($this->name) || strlen($this->name) <= 0) {
            throw new AccountNameInvalidException(
                'Account name is required',
                ResponseStatus::BAD_REQUEST->value
            );
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUuid(): ?UuidInterface
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
