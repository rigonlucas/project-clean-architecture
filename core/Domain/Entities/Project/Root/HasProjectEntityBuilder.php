<?php

namespace Core\Domain\Entities\Project\Root;

use Carbon\CarbonInterface;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
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

    public static function forGet(
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
