<?php

namespace Tests\Unit\Entities\Project;

use Core\Application\Project\Commons\Exceptions\ProjectStatusUnableException;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group project_entity
 */
class ProjectEntityTest extends TestCase
{
    public static function statusInvalidForCreationProvider(): array
    {
        return [
            [StatusProjectEnum::FINISHED],
            [StatusProjectEnum::CANCELED],
            [StatusProjectEnum::VALIDATION],
            [StatusProjectEnum::ON_HOLD],
            [StatusProjectEnum::ARCHIVED],
            [StatusProjectEnum::REVIEW],
            [StatusProjectEnum::DELIVERED],
        ];
    }

    public function test_create_project_success_without_dates()
    {
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();

        $this->expectNotToPerformAssertions();
    }

    public function test_create_project_success_with_dates()
    {
        $this->expectNotToPerformAssertions();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
            startAt: now(),
            finishAt: now()->addDays(1)
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }

    public function test_create_project_success_with_date_start_at_null_and_finished_at_now()
    {
        $this->expectException(DateRequiredException::class);
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
            finishAt: now()
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }

    public function test_create_project_fail_with_date_start_at_after_finished_at()
    {
        $this->expectException(DateMustBeBeforeOtherException::class);
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
            startAt: now()->addDays(1),
            finishAt: now()
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }

    public function test_create_project_fail_with_date_start_at_equal_finished_at()
    {
        $this->expectException(DatesMustBeDifferntsException::class);
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
            startAt: now(),
            finishAt: now()
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }

    public function test_create_project_fail_with_date_start_at_before_now()
    {
        $this->expectException(DateMustBeInCurrentDayException::class);
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
            startAt: now()->subDay(),
            finishAt: now()
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }

    public function test_create_project_success_with_status_pending()
    {
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::PENDING,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();

        $this->expectNotToPerformAssertions();
    }

    public function test_create_project_success_with_status_in_progress()
    {
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::IN_PROGRESS,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();

        $this->expectNotToPerformAssertions();
    }

    /**
     * @dataProvider statusInvalidForCreationProvider
     */
    public function test_create_project_fail_with_invalid_status(StatusProjectEnum $status)
    {
        $this->expectException(ProjectStatusUnableException::class);
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountId: 1
            ),
            account: AccountEntity::forIdentify(
                id: 1
            ),
            uuid: Uuid::uuid7(),
            status: $status,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
    }
}
