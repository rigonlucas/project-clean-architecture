<?php

namespace Core\Application\User\Commons\Gateways;

use Core\Domain\Entities\Shared\User\Root\UserEntity;

interface UserCommandInterface
{
    public function create(UserEntity $userEntity): UserEntity;

    public function update(UserEntity $userEntity): UserEntity;

    public function changeRole(UserEntity $userEntity): void;
}