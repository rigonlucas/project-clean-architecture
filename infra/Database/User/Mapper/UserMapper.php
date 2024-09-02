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
use Ramsey\Uuid\Uuid;
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
            ->select(['name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->toBase()
            ->find($id);
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(Uuid::fromString($userModel->account_uuid)),
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
            ->select(['name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('uuid', '=', $uuid)
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(
                FrameworkService::getInstance()->uuid()->uuidFromString($userModel->account_uuid)
            ),
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
            ->select(['name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('email', '=', $email)
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(Uuid::fromString($userModel->account_uuid)),
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
            ->select(['name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('email', '=', $email)
            ->where('uuid', '=', $uuid->toString())
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(
                FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid)
            ),
            birthday: new DateTime($userModel->birthday),
            role: $userModel->role
        );
    }

    public function existsEmail(string $email): bool
    {
        return User::query()->where('email', '=', $email)->exists();
    }

    public function existsUuid(int $id): bool
    {
        return User::query()->where('uuid', '=', $id)->exists();
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
            ->select(['name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('account_uuid', '=', $account->getUuid())
            ->with('account:id,name,uuid')
            ->paginate(perPage: $paginationData->perPage, page: $paginationData->page);

        $userCollection = new UserCollection($authUser);
        foreach ($userModels->items() as $userModel) {
            $userCollection->add(
                UserEntity::forDetail(
                    name: $userModel->name,
                    email: $userModel->email,
                    uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
                    account: AccountEntity::forDetail(
                        FrameworkService::getInstance()->uuid()->uuidFromString($userModel->account->uuid),
                        $userModel->account->name,
                    ),
                    birthday: new DateTime($userModel->birthday),
                    role: $userModel->role
                )
            );
        }

        return DefaultPaginationConverter::convert($userCollection, $userModels);
    }
}
