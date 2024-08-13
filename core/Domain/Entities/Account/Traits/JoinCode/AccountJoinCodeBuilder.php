<?php

namespace Core\Domain\Entities\Account\Traits\JoinCode;

use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use DateTimeInterface;

trait AccountJoinCodeBuilder
{
    public static function forDetail(
        int $id,
        string $code,
        int $accountid,
        DateTimeInterface $expiresAt,
        ?int $userId
    ): AccountJoinCodeEntity {
        $accountJoinCode = new AccountJoinCodeEntity();
        $accountJoinCode->setId($id);
        $accountJoinCode->setCode($code);
        $accountJoinCode->setExpiresAt($expiresAt);
        $accountJoinCode->setAccountId($accountid);
        $accountJoinCode->setUserId($userId);

        return $accountJoinCode;
    }
}