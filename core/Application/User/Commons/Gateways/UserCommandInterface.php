<?php

namespace Core\Application\User\Commons\Gateways;

use Core\Application\User\Commons\Entities\User\UserEntity;

interface UserCommandInterface
{
    public function create(UserEntity $userEntity): UserEntity;

    public function update(UserEntity $userEntity): UserEntity;
}