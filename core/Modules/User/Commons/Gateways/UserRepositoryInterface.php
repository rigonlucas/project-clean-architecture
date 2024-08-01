<?php

namespace Core\Modules\User\Commons\Gateways;

use Core\Modules\User\Commons\Entities\User\UserEntity;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity;

    public function findByUuid(string $uuid): ?UserEntity;

    public function findByEmail(string $email): ?UserEntity;

    public function existsEmail(string $email): bool;

    public function existsId(int $id): bool;
}
