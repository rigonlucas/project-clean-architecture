<?php

namespace Core\Modules\User\Commons\Gateways;

use Core\Modules\User\Commons\Entities\User\UserEntity;

interface UserCommandInterface
{
    public function create(UserEntity $userEntity): UserEntity;

    public function update(UserEntity $userEntity): UserEntity;
}