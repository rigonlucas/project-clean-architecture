<?php

namespace Core\Domain\Entities\Project\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Ramsey\Uuid\UuidInterface;

trait HasProjectEntityBuilder
{
    /**
     * @param string $name
     * @param string $description
     * @param UserEntity $user
     * @param AccountEntity $account
     * @param UuidInterface $uuid
     * @param Carbon|null $startAt
     * @param Carbon|null $finishAt
     * @return ProjectEntity
     * @throws ForbidenException
     * @throws DateMustBeBeforeOtherException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     */
    public static function forCreate(
        string $name,
        string $description,
        UserEntity $user,
        AccountEntity $account,
        UuidInterface $uuid,
        CarbonInterface $startAt = null,
        CarbonInterface $finishAt = null
    ): ProjectEntity {
        $project = new ProjectEntity();
        $project->setUser($user);
        $project->setAccount($account);
        $project->canCreateProject();

        $project->setStartAt($startAt);
        $project->setFinishAt($finishAt);
        $project->datesValidation();

        $project->setName($name);
        $project->setDescription($description);
        $project->setUuid($uuid);

        return $project;
    }

}
