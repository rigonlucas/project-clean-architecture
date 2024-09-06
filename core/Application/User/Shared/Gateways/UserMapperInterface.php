<?php

namespace Core\Application\User\Shared\Gateways;

use Core\Domain\Collections\User\UserCollection;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Collections\Paginations\Inputs\DefaultPaginationData;
use Ramsey\Uuid\UuidInterface;

interface UserMapperInterface
{
    public function findByUuid(string $uuid): ?UserEntity;

    public function findByEmail(string $email): ?UserEntity;

    public function findByEmailAndUuid(string $email, UuidInterface $uuid): ?UserEntity;

    public function existsEmail(string $email): bool;

    public function existsUuid(UuidInterface $uuid): bool;

    public function paginatedAccountUserList(
        AccountEntity $account,
        DefaultPaginationData $paginationData,
        UserEntity $authUser
    ): UserCollection;
}
