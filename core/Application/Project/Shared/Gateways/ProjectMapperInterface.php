<?php

namespace Core\Application\Project\Shared\Gateways;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;

interface ProjectMapperInterface
{
    public function findByUuid(string $uuid, UserEntity $userEntity): ?ProjectEntity;

    public function existsByName(string $name, AccountEntity $accountEntity): bool;

    public function notExistsByName(string $name, AccountEntity $accountEntity): bool;
}