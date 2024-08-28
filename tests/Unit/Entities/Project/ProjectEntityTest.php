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
        $statuses = StatusProjectEnum::cases();
        $statuses = array_filter([$statuses], fn($status) => !in_array($status, [
            StatusProjectEnum::BACKLOG,
            StatusProjectEnum::PENDING,
            StatusProjectEnum::IN_PROGRESS
        ]));
        return [
            [StatusProjectEnum::FINISHED],
            [StatusProjectEnum::CANCELED],
            [StatusProjectEnum::VALIDATION],
            [StatusProjectEnum::ON_HOLD],
            [StatusProjectEnum::ARCHIVED],
            [StatusProjectEnum::REVIEW],
            [StatusProjectEnum::DEPLOYED],
        ];
    }

    public function test_create_project_success_without_dates()
    {
        ProjectEntity::forCreate(
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

        $this->expectNotToPerformAssertions();
    }

    public function test_create_project_success_with_dates()
    {
        $this->expectNotToPerformAssertions();
        ProjectEntity::forCreate(
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
    }

    public function test_create_project_success_with_date_start_at_null_and_finished_at_now()
    {
        $this->expectException(DateRequiredException::class);
        ProjectEntity::forCreate(
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
    }

    public function test_create_project_fail_with_date_start_at_after_finished_at()
    {
        $this->expectException(DateMustBeBeforeOtherException::class);
        ProjectEntity::forCreate(
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
    }

    public function test_create_project_fail_with_date_start_at_equal_finished_at()
    {
        $this->expectException(DatesMustBeDifferntsException::class);
        ProjectEntity::forCreate(
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
    }

    public function test_create_project_fail_with_date_start_at_before_now()
    {
        $this->expectException(DateMustBeInCurrentDayException::class);
        ProjectEntity::forCreate(
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
    }

    public function test_create_project_success_with_status_pending()
    {
        ProjectEntity::forCreate(
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

        $this->expectNotToPerformAssertions();
    }

    public function test_create_project_success_with_status_in_progress()
    {
        ProjectEntity::forCreate(
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

        $this->expectNotToPerformAssertions();
    }

    /**
     * @dataProvider statusInvalidForCreationProvider
     */
    public function test_create_project_fail_with_invalid_status(StatusProjectEnum $status)
    {
        $this->expectException(ProjectStatusUnableException::class);
        ProjectEntity::forCreate(
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
    }
}
