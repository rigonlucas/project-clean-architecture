<?php

namespace Core\Application\Account\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;

interface AccountCommandInterface
{
    public function createAccount(AccountEntity $accountEntity): AccountEntity;
}