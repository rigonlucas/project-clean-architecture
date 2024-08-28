<?php

namespace Core\Domain\Entities\Project;

use Carbon\CarbonInterface;
use Core\Application\Project\Commons\Exceptions\ProjectStatusUnableException;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\Traits\HasProjectEntityBuilder;
use Core\Domain\Entities\Project\Traits\ProjectEntityAcessors;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\UuidInterface;

class ProjectEntity
{
    use ProjectEntityAcessors;
    use HasProjectEntityBuilder;

    private int $id;
    private UuidInterface $uuid;
    private string $name;
    private string $description;
    private ?UserEntity $user = null;
    private ?AccountEntity $account = null;
    private ?CarbonInterface $startAt = null;
    private ?CarbonInterface $finishAt = null;
    private StatusProjectEnum $status;
    private array $allowedStatusToCreateProject = [
        StatusProjectEnum::BACKLOG->value,
        StatusProjectEnum::PENDING->value,
        StatusProjectEnum::IN_PROGRESS->value
    ];

    private function __construct()
    {
    }

    /**
     * @throws ForbidenException
     * @throws ProjectStatusUnableException
     */
    private function canCreateProject(): void
    {
        if (is_null($this->user)) {
            throw new ForbidenException('An user is required to create a project');
        }

        if ($this->user->hasNotAnyPermissionFromArray([UserRoles::ADMIN, UserRoles::EDITOR])) {
            throw new ForbidenException('You do not have permission to create a project');
        }

        if (is_null($this->account)) {
            throw new ForbidenException('An account is required to create a project');
        }

        if ($this->account->getId() !== $this->user->getAccount()->getId()) {
            throw new ForbidenException('Your account is not allowed to create a project in this account');
        }

        if ($this->status->isNotIn($this->allowedStatusToCreateProject)) {
            throw new ProjectStatusUnableException(
                "Only BACKLOG, IN PROGRESS and PENDING status are allowed to create a project, " . $this->status->value . " given"
            );
        }
    }

    /**
     * @throws DateMustBeBeforeOtherException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     */
    private function datesValidation(): void
    {
        if (!is_null($this->startAt) && !is_null($this->finishAt)) {
            if ($this->startAt->isAfter($this->finishAt)) {
                throw new DateMustBeBeforeOtherException('The start date must be before the end date');
            }

            if ($this->startAt->isSameDay($this->finishAt)) {
                throw new DatesMustBeDifferntsException('The start date and finish date must be differents days');
            }
        }

        if (is_null($this->startAt)) {
            if (!is_null($this->finishAt)) {
                throw new DateRequiredException('The start date is required when the end date is informed');
            }
        }

        if ($this->startAt) {
            if ($this->startAt->isBefore(now()->startOfDay())) {
                throw new DateMustBeInCurrentDayException('The start date must be before the current day');
            }
        }
    }
}
