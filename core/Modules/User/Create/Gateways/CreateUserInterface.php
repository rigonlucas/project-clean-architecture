<?php

namespace Core\Modules\User\Create\Gateways;

use Core\Modules\User\Commons\Entities\UserEntity;

interface CreateUserInterface
{
    public function create(UserEntity $userEntity): UserEntity;
}