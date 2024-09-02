<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;

interface ProjectMapperInterface
{
    public function findByUuid(string $uuid, UserEntity $userEntity): ?ProjectEntity;

    public function existsByName(string $name, AccountEntity $accountEntity): bool;

    public function notExistsByName(string $name, AccountEntity $accountEntity): bool;
}