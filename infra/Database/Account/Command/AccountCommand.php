<?php

namespace Infra\Database\Account\Command;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Shared\Gateways\AccountCommandInterface;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;

class AccountCommand implements AccountCommandInterface
{

    public function createAccount(AccountEntity $accountEntity): AccountEntity
    {
        $accountModel = new Account();
        $accountModel->name = $accountEntity->getName();
        $accountModel->uuid = $accountEntity->getUuid();
        $accountModel->owner_user_id = $accountEntity->getUserEntity()->getId();
        $accountModel->save();

        User::query()
            ->where('id', '=', $accountEntity->getUserEntity()->getId())
            ->update([
                'account_uuid' => $accountEntity->getUuid()->toString()
            ]);

        return $accountEntity;
    }

    public function useAccountJoinCode(AccountEntity $accountEntity): void
    {
        AccountJoinCode::query()
            ->where('code', '=', $accountEntity->getJoinCodeEntity()->getCode())
            ->update([
                'user_id' => $accountEntity->getUserEntity()->getId()
            ]);
        User::query()
            ->where('id', '=', $accountEntity->getUserEntity()->getId())
            ->update([
                'account_uuid' => $accountEntity->getUuid()->toString()
            ]);
    }
}
