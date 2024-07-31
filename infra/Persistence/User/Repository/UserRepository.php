<?php

namespace Infra\Persistence\User\Repository;

use App\Models\User;
use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use DateTime;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid'])
            ->find($id);
        if ($userModel) {
            return UserEntity::details(
                id: $userModel->id,
                name: $userModel->name,
                email: $userModel->email,
                uuid: AppAdapter::getInstance()->uuidFromString($userModel->uuid),
                birthday: new DateTime($userModel->birthday)
            );
        }

        return null;
    }

    public function findByUuid(string $uuid): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid'])
            ->where('uuid', '=', $uuid)
            ->first();
        if ($userModel) {
            return UserEntity::details(
                id: $userModel->id,
                name: $userModel->name,
                email: $userModel->email,
                uuid: AppAdapter::getInstance()->uuidFromString($userModel->uuid),
                birthday: new DateTime($userModel->birthday),
            );
        }

        return null;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid'])
            ->where('email', '=', $email)
            ->first();
        if ($userModel) {
            return UserEntity::details(
                id: $userModel->id,
                name: $userModel->name,
                email: $userModel->email,
                uuid: AppAdapter::getInstance()->uuidFromString($userModel->uuid),
                birthday: new DateTime($userModel->birthday)
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
