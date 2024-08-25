<?php

namespace Infra\Database\User\Repository;

use App\Models\User;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Domain\Collections\User\UserCollection;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Collections\Paginations\Inputs\DefaultPaginationData;
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

    public function existsEmail(string $email): bool
    {
        return User::query()->where('email', '=', $email)->exists();
    }

    public function existsId(int $id): bool
    {
        return User::query()->where('id', '=', $id)->exists();
    }

    public function paginatedAccountUserList(
        AccountEntity $account,
        DefaultPaginationData $paginationData
    ): UserCollection {
        $userModels = User::query()
            ->select(['id', 'name', 'email', 'birthday', 'uuid', 'account_id', 'role'])
            ->where('account_id', '=', $account->getId())
            ->with('account:id,name,uuid')
            ->paginate(perPage: $paginationData->perPage, page: $paginationData->page);

        $userCollection = new UserCollection();
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

        return $userCollection
            ->setCurrentPage($userModels->currentPage())
            ->setFirstPageUrl($userModels->url(1))
            ->setFrom($userModels->firstItem())
            ->setLastPage($userModels->lastPage())
            ->setLastPageUrl($userModels->url($userModels->lastPage()))
            ->setLinks($userModels->linkCollection()->toArray())
            ->setNextPageUrl($userModels->nextPageUrl())
            ->setPath($userModels->path())
            ->setPerPage($userModels->perPage())
            ->setPrevPageUrl($userModels->previousPageUrl())
            ->setTo($userModels->lastItem())
            ->setTotal($userModels->total());
    }
}
