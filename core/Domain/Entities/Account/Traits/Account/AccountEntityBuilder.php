<?php

namespace Core\Domain\Entities\Account\Traits\Account;

use Core\Application\Account\Commons\Exceptions\AccountNameInvalidException;
use Core\Domain\Entities\Account\AccountEntity;

trait AccountEntityBuilder
{
    /**
     * @throws AccountNameInvalidException
     */
    public static function forCreate(
        string $name,
        string $uuid
    ): AccountEntity {
        $account = new AccountEntity();
        $account->setName($name);
        $account->setUuid($uuid);
        $account->validateAccountName();

        return $account;
    }

    public static function forDetail(
        int $id,
        string $name,
        string $uuid
    ): AccountEntity {
        $account = new AccountEntity();
        $account->setId($id);
        $account->setName($name);
        $account->setUuid($uuid);

        return $account;
    }
}
