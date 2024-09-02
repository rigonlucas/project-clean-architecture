<?php

namespace Core\Domain\Entities\Account\Traits\JoinCode;

use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

trait AccountJoinCodeBuilder
{
    public static function forDetail(
        UuidInterface $uuid,
        string $code,
        UuidInterface $accountUuid,
        DateTimeInterface $expiresAt
    ): AccountJoinCodeEntity {
        $accountJoinCode = new AccountJoinCodeEntity();
        $accountJoinCode->setUuid($uuid);
        $accountJoinCode->setCode($code);
        $accountJoinCode->setExpiresAt($expiresAt);
        $accountJoinCode->setAccountUuid($accountUuid);
        $accountJoinCode->validateJoinCode();

        return $accountJoinCode;
    }
}