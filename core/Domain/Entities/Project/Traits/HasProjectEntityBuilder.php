<?php

namespace Core\Domain\Entities\Project\Traits;

use Carbon\CarbonInterface;
use Core\Application\Project\Commons\Exceptions\ProjectStatusUnableException;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Ramsey\Uuid\UuidInterface;

trait HasProjectEntityBuilder
{
    /**
     * @throws DateMustBeBeforeOtherException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     * @throws ForbidenException
     * @throws ProjectStatusUnableException
     */
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
