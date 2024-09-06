<?php

namespace Tests\Unit\Entities\Project;

use Core\Application\Project\Shared\Exceptions\ProjectStatusUnableException;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Permissions\UserRoles;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('project_entity')]
class ProjectEntityTest extends TestCase
{
    public static function statusInvalidForCreationProvider(): array
    {
        return [
            'finished' => [StatusProjectEnum::FINISHED],
            'canceled' => [StatusProjectEnum::CANCELED],
            'validation' => [StatusProjectEnum::VALIDATION],
            'on hold' => [StatusProjectEnum::ON_HOLD],
            'archived' => [StatusProjectEnum::ARCHIVED],
            'review' => [StatusProjectEnum::REVIEW],
            'delivered' => [StatusProjectEnum::DELIVERED],
        ];
    }

    public function test_create_project_success_without_dates()
    {
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();

        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::PENDING,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();

        $this->expectNotToPerformAssertions();
    }

    public function test_create_project_success_with_status_in_progress()
    {
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
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
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
            uuid: Uuid::uuid7(),
            status: $status,
        );
        $projectEntity->canChangeProject();
        $projectEntity->datesValidation();
        $projectEntity->canCreate();
    }
}
