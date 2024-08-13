<?php

namespace Infra\Database\Account\Command;

use App\Models\Account;
use App\Models\AccountJoinCode;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;

class AccountCommand implements AccountCommandInterface
{

    public function createAccount(AccountEntity $accountEntity): AccountEntity
    {
        $accountModel = new Account();
        $accountModel->name = $accountEntity->getName();
        $accountModel->uuid = $accountEntity->getUuid();
        $accountModel->save();

        return $accountEntity->setId($accountModel->id);
    }

    public function useAccountJoinCode(AccountEntity $accountEntity, UserEntity $userEntity): void
    {
        AccountJoinCode::query()
            ->where('code', $accountEntity->getUuid())
            ->update([
                'user_id' => $userEntity->getId()
            ]);
    }
}
