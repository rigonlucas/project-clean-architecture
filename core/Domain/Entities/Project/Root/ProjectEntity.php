<?php

namespace Core\Domain\Entities\Project\Root;

use Carbon\CarbonInterface;
use Core\Application\Project\Commons\Exceptions\ProjectStatusUnableException;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
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
    use HasProjectEntityBuilder;

    public const array PERMISSIONS_TO_MANAGEMEMT = [
        UserRoles::ADMIN,
        UserRoles::EDITOR
    ];
    public const array STATUS_TO_CREATE = [
        StatusProjectEnum::BACKLOG->value,
        StatusProjectEnum::PENDING->value,
        StatusProjectEnum::IN_PROGRESS->value
    ];
    private UuidInterface $uuid;
    private string $name;
    private string $description;
    private ?UserEntity $user = null;
    private ?AccountEntity $account = null;
    private ?CarbonInterface $startAt = null;
    private ?CarbonInterface $finishAt = null;
    private StatusProjectEnum $status;

    private function __construct()
    {
    }

    /**
     * @throws ForbidenException
     */
    public function canChangeProject(): void
    {
        if (is_null($this->user)) {
            throw new ForbidenException('An user is required to change a project');
        }

        if ($this->user->hasNotAnyPermissionFromArray(self::PERMISSIONS_TO_MANAGEMEMT)) {
            throw new ForbidenException('You do not have permission to create a project');
        }

        if (is_null($this->account)) {
            throw new ForbidenException('An account is required to create a project');
        }

        if (!$this->account->getUuid()->equals($this->user->getAccount()->getUuid())) {
            throw new ForbidenException('Your account is not allowed to create a project in this account');
        }
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getAccount(): AccountEntity
    {
        return $this->account;
    }

    public function setAccount(AccountEntity $account): void
    {
        $this->account = $account;
    }

    /**
     * @throws DateMustBeBeforeOtherException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     */
    public function datesValidation(): void
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

    /**
     * @throws ProjectStatusUnableException
     */
    public function canCreate(): void
    {
        if ($this->status->isNotIn(ProjectEntity::STATUS_TO_CREATE)) {
            throw new ProjectStatusUnableException(
                "Only BACKLOG, IN PROGRESS and PENDING status are allowed to create a project, " . $this->status->value . " given"
            );
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
    }

    public function getStartAt(): ?CarbonInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?CarbonInterface $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getFinishAt(): ?CarbonInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(?CarbonInterface $finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    public function getStatus(): StatusProjectEnum
    {
        return $this->status;
    }

    public function setStatus(StatusProjectEnum $status): void
    {
        $this->status = $status;
    }
}
