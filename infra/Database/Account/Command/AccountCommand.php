<?php

namespace Infra\Database\Account\Command;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Domain\Entities\Account\AccountEntity;

class AccountCommand implements AccountCommandInterface
{

    public function createAccount(AccountEntity $accountEntity): AccountEntity
    {
        $accountModel = new Account();
        $accountModel->name = $accountEntity->getName();
        $accountModel->uuid = $accountEntity->getUuid();
        $accountModel->save();

        User::query()
            ->where('id', '=', $accountEntity->getUserEntity()->getId())
            ->update([
                'account_id' => $accountModel->id
            ]);

        return $accountEntity->setId($accountModel->id);
    }

    public function useAccountJoinCode(AccountEntity $accountEntity): void
    {
        AccountJoinCode::query()
            ->where('code', '=', $accountEntity->getUuid())
            ->update([
                'user_id' => $accountEntity->getUserEntity()->getId()
            ]);
        User::query()
            ->where('id', '=', $accountEntity->getUserEntity()->getId())
            ->update([
                'account_id' => $accountEntity->getId()
            ]);
    }
}
