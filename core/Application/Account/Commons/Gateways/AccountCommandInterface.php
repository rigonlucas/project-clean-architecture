<?php

namespace Core\Application\Account\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;

interface AccountCommandInterface
{
    public function createAccount(AccountEntity $accountEntity, UserEntity $userEntity): AccountEntity;

    public function useAccountJoinCode(AccountEntity $accountEntity, UserEntity $userEntity): void;
}