<?php

namespace Core\Domain\Entities\Shared\Account\Root;

use Core\Application\Account\Shared\Exceptions\AccountNameInvalidException;
use Ramsey\Uuid\UuidInterface;

trait AccountEntityBuilder
{
    /**
     * @throws AccountNameInvalidException
     */
    public static function forCreate(
        string $name,
        UuidInterface $uuid
    ): AccountEntity {
        $account = new AccountEntity();
        $account->setName($name);
        $account->setUuid($uuid);
        $account->validateAccountName();

        return $account;
    }

    public static function forDetail(
        UuidInterface $uuid,
        string $name
    ): AccountEntity {
        $account = new AccountEntity();
        $account->setUuid($uuid);
        $account->setName($name);

        return $account;
    }

    public static function forIdentify(
        UuidInterface $uuid
    ): AccountEntity {
        $account = new AccountEntity();
        $account->setUuid($uuid);

        return $account;
    }
}
