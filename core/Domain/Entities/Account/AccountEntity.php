<?php

namespace Core\Domain\Entities\Account;

use Core\Application\Account\Commons\Exceptions\AccountNameInvalidException;
use Core\Domain\Entities\Account\Traits\Account\AccountEntityAcessors;
use Core\Domain\Entities\Account\Traits\Account\AccountEntityBuilder;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Http\ResponseStatus;

class AccountEntity
{
    use AccountEntityAcessors;
    use AccountEntityBuilder;

    private ?int $id = null;
    private ?string $name = null;
    private ?string $uuid = null;
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
}
