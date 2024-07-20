<?php

namespace Core\Modules\User\Commons\Gateways;

use Core\Modules\User\Commons\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity;
}
