<?php

namespace Infra\Database\Account\Command;

use App\Models\Account;
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

        return $accountEntity->setId($accountModel->id);
    }
}
