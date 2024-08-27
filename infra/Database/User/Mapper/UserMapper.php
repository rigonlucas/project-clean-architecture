<?php

namespace Infra\Database\User\Mapper;

use App\Models\User;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Domain\Collections\User\UserCollection;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Collections\Paginations\Inputs\DefaultPaginationData;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use DateTime;
use Exception;
use Infra\Services\Framework\DefaultPaginationConverter;
use Infra\Services\Framework\FrameworkService;
use Ramsey\Uuid\UuidInterface;

class UserMapper implements UserMapperInterface
{
    /**
     * @throws InvalidEmailException
     * @throws Exception
     */
    public function findById(int $id): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
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
            birthday: new DateTime($userModel->birthday),
            role: $userModel->role
        );
    }

    /**
     * @throws InvalidEmailException
     * @throws Exception
     */
    public function findByUuid(string $uuid): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
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
            role: $userModel->role
        );
    }

    /**
     * @throws InvalidEmailException
     * @throws Exception
     */
    public function findByEmail(string $email): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
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
            birthday: new DateTime($userModel->birthday),
            role: $userModel->role
        );
    }

    /**
     * @throws InvalidEmailException
     * @throws Exception
     */
    public function findByEmailAndUuid(string $email, UuidInterface $uuid): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
            ->where('email', '=', $email)
            ->where('uuid', '=', $uuid->toString())
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
            role: $userModel->role
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

    /**
     * @throws InvalidEmailException
     */
    public function paginatedAccountUserList(
        AccountEntity $account,
        DefaultPaginationData $paginationData,
        UserEntity $authUser
    ): UserCollection {
        $userModels = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
            ->where('account_id', '=', $account->getId())
            ->with('account:id,name,uuid')
            ->paginate(perPage: $paginationData->perPage, page: $paginationData->page);

        $userCollection = new UserCollection($authUser);
        foreach ($userModels->items() as $userModel) {
            $userCollection->add(
                UserEntity::forDetail(
                    id: $userModel->id,
                    name: $userModel->name,
                    email: $userModel->email,
                    uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
                    account: AccountEntity::forDetail(
                        $userModel->account->id,
                        $userModel->account->name,
                        FrameworkService::getInstance()->uuid()->uuidFromString($userModel->account->uuid)
                    ),
                    birthday: new DateTime($userModel->birthday),
                    role: $userModel->role
                )
            );
        }

        return DefaultPaginationConverter::convert($userCollection, $userModels);
    }
}
