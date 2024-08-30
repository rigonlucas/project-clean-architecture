<?php

namespace Core\Domain\Entities\Project\Traits;

use Carbon\CarbonInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Ramsey\Uuid\UuidInterface;

trait HasProjectEntityBuilder
{
    public static function forCreate(
        string $name,
        string $description,
        UserEntity $user,
        AccountEntity $account,
        UuidInterface $uuid,
        StatusProjectEnum $status,
        CarbonInterface $startAt = null,
        CarbonInterface $finishAt = null
    ): ProjectEntity {
        $project = new ProjectEntity();
        $project->setUser($user);
        $project->setAccount($account);
        $project->setStatus($status);

        $project->setStartAt($startAt);
        $project->setFinishAt($finishAt);

        $project->setName($name);
        $project->setDescription($description);
        $project->setUuid($uuid);

        return $project;
    }

    public static function forUpdate(
        int $id,
        string $name,
        string $description,
        UserEntity $user,
        AccountEntity $account,
        UuidInterface $uuid,
        StatusProjectEnum $status,
        CarbonInterface $startAt = null,
        CarbonInterface $finishAt = null
    ): ProjectEntity {
        $project = new ProjectEntity();
        $project->setId($id);
        $project->setStatus($status);
        $project->setUser($user);
        $project->setAccount($account);

        $project->setStartAt($startAt);
        $project->setFinishAt($finishAt);

        $project->setName($name);
        $project->setDescription($description);
        $project->setUuid($uuid);

        return $project;
    }

}
