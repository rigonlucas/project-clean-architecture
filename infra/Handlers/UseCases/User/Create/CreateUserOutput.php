<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;

readonly class CreateUserOutput
{
    public function __construct(public UserEntity $userEntity, public AccountEntity $accountEntity)
    {
    }
}
