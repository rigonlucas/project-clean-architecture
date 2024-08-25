<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;

interface ProjectMapperInterface
{
    public function findByid(int $id): ?ProjectEntity;

    public function findByUuid(string $uuid): ?ProjectEntity;

    public function existsByName(string $name, AccountEntity $accountEntity): bool;

    public function notExistsByName(string $name, AccountEntity $accountEntity): bool;
}