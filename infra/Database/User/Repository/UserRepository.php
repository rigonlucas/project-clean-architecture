<?php

namespace Infra\Database\User\Repository;

use App\Models\User;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\InvalidEmailException;
use DateTime;
use Infra\Services\Framework\FrameworkService;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @throws InvalidEmailException
     */
    public function findById(int $id): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid'])
            ->find($id);
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify($userModel->account_id),
            birthday: new DateTime($userModel->birthday)
        );
    }

    public function findByUuid(string $uuid): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id'])
            ->where('uuid', '=', $uuid)
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify($userModel->account_id),
            birthday: new DateTime($userModel->birthday),
        );
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid'])
            ->where('email', '=', $email)
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify($userModel->account_id),
            birthday: new DateTime($userModel->birthday)
        );
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
