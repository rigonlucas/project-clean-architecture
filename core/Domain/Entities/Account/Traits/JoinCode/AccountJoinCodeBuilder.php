<?php

namespace Core\Domain\Entities\Account\Traits\JoinCode;

use Core\Domain\Entities\Account\AccountJoinCodeEntity;

trait AccountJoinCodeBuilder
{
    public function forDetail(
        int $id,
        string $code,
        int $account_id,
        int $user_id
    ): AccountJoinCodeEntity {
        $accountJoinCode = new AccountJoinCodeEntity();
        $accountJoinCode->setId($id);
        $accountJoinCode->setCode($code);
        $accountJoinCode->setAccountId($account_id);
        $accountJoinCode->setUserId($user_id);

        return $accountJoinCode;
    }
}