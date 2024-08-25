<?php

namespace Infra\Database\Project\Mapper;

use App\Models\Project;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Exception;

class ProjectMapper implements ProjectMapperInterface
{

    public function findByid(int $id): ?ProjectEntity
    {
        throw new Exception('Method not implemented');
    }

    public function findByUuid(string $uuid): ?ProjectEntity
    {
        throw new Exception('Method not implemented');
    }

    public function notExistsByName(string $name, AccountEntity $accountEntity): bool
    {
        return !$this->existsByName($name, $accountEntity);
    }

    public function existsByName(string $name, AccountEntity $accountEntity): bool
    {
        return Project::query()
            ->where('name', 'like', $name)
            ->where('account_id', '=', $accountEntity->getId())
            ->exists();
    }
}
