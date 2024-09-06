<?php

namespace Core\Application\Account\Shared\Gateways;

use Core\Domain\Entities\Shared\Account\Root\AccountEntity;

interface AccountCommandInterface
{
    public function createAccount(AccountEntity $accountEntity): AccountEntity;

    public function useAccountJoinCode(AccountEntity $accountEntity): void;
}