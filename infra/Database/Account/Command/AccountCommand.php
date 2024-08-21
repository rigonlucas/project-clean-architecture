<?php

namespace Infra\Database\Account\Command;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;

class AccountCommand implements AccountCommandInterface
{

    public function createAccount(AccountEntity $accountEntity, UserEntity $userEntity): AccountEntity
    {
        $accountModel = new Account();
        $accountModel->name = $userEntity->getName();
        $accountModel->uuid = $accountEntity->getUuid();
        $accountModel->save();

        User::query()
            ->where('id', $userEntity->getId())
            ->update([
                'account_id' => $accountModel->id
            ]);

        return $accountEntity->setId($accountModel->id);
    }

    public function useAccountJoinCode(AccountEntity $accountEntity, UserEntity $userEntity): void
    {
        AccountJoinCode::query()
            ->where('code', $accountEntity->getUuid())
            ->update([
                'user_id' => $userEntity->getId()
            ]);
        User::query()
            ->where('id', $userEntity->getId())
            ->update([
                'account_id' => $accountEntity->getId()
            ]);
    }
}
