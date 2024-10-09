<?php

namespace Infra\Database\User\Mapper;

use App\Models\User;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Domain\Collections\User\UserCollection;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
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
    public function findByUuid(string $uuid): ?UserEntity
    {
        $userModel = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('uuid', '=', $uuid)
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(
                uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->account_uuid)
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
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('email', '=', $email)
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
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
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('email', '=', $email)
            ->where('uuid', '=', $uuid->toString())
            ->toBase()
            ->first();
        if (!$userModel) {
            return null;
        }

        return UserEntity::forDetail(
            id: $userModel->id,
            name: $userModel->name,
            email: $userModel->email,
            uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
            account: AccountEntity::forIdentify(
                uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid)
            ),
            birthday: new DateTime($userModel->birthday),
            role: $userModel->role
        );
    }

    public function existsEmail(string $email): bool
    {
        return User::query()->where('email', '=', $email)->exists();
    }

    public function existsUuid(UuidInterface $uuid): bool
    {
        return User::query()->where('uuid', '=', $uuid->toString())->exists();
    }

    /**
     * @throws InvalidEmailException
     * @throws Exception
     */
    public function paginatedAccountUserList(
        AccountEntity $account,
        DefaultPaginationData $paginationData,
        UserEntity $authUser
    ): UserCollection {
        $userModels = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_uuid', 'role'])
            ->where('account_uuid', '=', $account->getUuid())
            ->with('account:id,name,uuid')
            ->paginate(perPage: $paginationData->perPage, page: $paginationData->page);

        $userCollection = new UserCollection($authUser);
        foreach ($userModels->items() as $userModel) {
            $userCollection->add(
                user: UserEntity::forDetail(
                    id: $userModel->id,
                    name: $userModel->name,
                    email: $userModel->email,
                    uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->uuid),
                    account: AccountEntity::forDetail(
                        uuid: FrameworkService::getInstance()->uuid()->uuidFromString($userModel->account->uuid),
                        name: $userModel->account->name,
                    ),
                    birthday: new DateTime($userModel->birthday),
                    role: $userModel->role
                )
            );
        }

        return DefaultPaginationConverter::convert($userCollection, $userModels);
    }
}
