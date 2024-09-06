<?php

namespace Infra\Database\Project\Mapper;

use App\Models\Project;
use Carbon\Carbon;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Ramsey\Uuid\Uuid;

class ProjectMapper implements ProjectMapperInterface
{

    public function findByUuid(string $uuid, UserEntity $userEntity): ?ProjectEntity
    {
        $projectModel = Project::query()
            ->select('uuid', 'name', 'description', 'status', 'start_at', 'finish_at')
            ->where('uuid', '=', $uuid)
            ->toBase()
            ->first();
        if (!$projectModel) {
            return null;
        }

        return ProjectEntity::forGet(
            name: $projectModel->name,
            description: $projectModel->description,
            user: $userEntity,
            account: $userEntity->getAccount(),
            uuid: Uuid::fromString($projectModel->uuid),
            status: StatusProjectEnum::from($projectModel->status),
            startAt: Carbon::make($projectModel->start_at),
            finishAt: Carbon::make($projectModel->finish_at)
        );
    }

    public function notExistsByName(string $name, AccountEntity $accountEntity): bool
    {
        return !$this->existsByName($name, $accountEntity);
    }

    public function existsByName(string $name, AccountEntity $accountEntity): bool
    {
        return Project::query()
            ->where('name', 'like', $name)
            ->where('account_uuid', '=', $accountEntity->getUuid())
            ->exists();
    }
}
