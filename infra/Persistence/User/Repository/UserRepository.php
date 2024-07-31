<?php

namespace Infra\Persistence\User\Repository;

use App\Models\User;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday'])
            ->find($id);
        if ($userModel) {
            return UserEntity::details(
                id: $userModel->id,
                name: $userModel->name,
                email: $userModel->email,
                birthday: $userModel->birthday
            );
        }

        return null;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday'])
            ->where('email', '=', $email)
            ->first();
        if ($userModel) {
            return UserEntity::details(
                id: $userModel->id,
                name: $userModel->name,
                email: $userModel->email,
                birthday: $userModel->birthday
            );
        }

        return null;
    }

    public function existsEmail(string $email): bool
    {
        return User::query()->where('email', '=', $email)->exists();
    }

    public function existsId(int $id): bool
    {
        return User::query()->where('id', '=', $id)->exists();
    }
}
