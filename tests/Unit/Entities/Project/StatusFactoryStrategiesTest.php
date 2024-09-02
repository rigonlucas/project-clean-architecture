<?php

namespace Tests\Unit\Entities\Project;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\StatusValidationFactory;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Dates\DateNotAllowedException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group project_entity_factory_strategies
 */
class StatusFactoryStrategiesTest extends TestCase
{
    /**
     * BACKLOG - ok
     * PENDING- ok
     * IN_PROGRESS- ok
     * FINISHED
     * CANCELED
     * VALIDATION
     * ON _HOLD
     * ARCHIVED
     * REVIEW
     * DELIVERED
     */
    public static function projectEntityProvider(): array
    {
        return [
            'Project entity' => [
                ProjectEntity::forUpdate(
                    name: 1,
                    description: 1,
                    user: UserEntity::forDetail(
                        name: 1,
                        email: 1,
                        uuid: Uuid::uuid7(),
                        account: AccountEntity::forIdentify(Uuid::uuid7()),
                        role: UserRoles::ADMIN
                    ),
                    account: AccountEntity::forIdentify(Uuid::uuid7()),
                    uuid: Uuid::uuid7(),
                    status: StatusProjectEnum::IN_PROGRESS,
                    startAt: now()->addDay(),
                    finishAt: now()->addDays(2)
                )
            ]
        ];
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_default_strategy_from_factory_has_only_success_case(ProjectEntity $projectEntity)
    {
        $this->expectNotToPerformAssertions();
        $projectEntity->setStatus(StatusProjectEnum::BACKLOG);
        StatusValidationFactory::make($projectEntity)->validate();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_pending_strategy_from_factory_success_case_when_finish_date_are_null(
        projectentity $projectEntity
    ) {
        $this->expectNotToPerformAssertions();
        $projectEntity->setStatus(StatusProjectEnum::PENDING);
        $projectEntity->setFinishAt(null);
        StatusValidationFactory::make($projectEntity)->validate();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_pending_strategy_from_factory_throw_case_when_finish_date_are_not_null(
        projectentity $projectEntity
    ) {
        $this->expectException(DateNotAllowedException::class);
        $projectEntity->setStatus(StatusProjectEnum::PENDING);
        StatusValidationFactory::make($projectEntity)->validate();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_in_progress_strategy_from_factory_fail_case_without_started_date(ProjectEntity $projectEntity)
    {
        $this->expectException(DateRequiredException::class);
        $projectEntity->setStatus(StatusProjectEnum::IN_PROGRESS);
        $projectEntity->setStartAt(null);
        StatusValidationFactory::make($projectEntity)->validate();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_in_progress_strategy_from_factory_fail_case_when_finished_date_are_setted(
        ProjectEntity $projectEntity
    ) {
        $this->expectException(DateNotAllowedException::class);
        $projectEntity->setStatus(StatusProjectEnum::IN_PROGRESS);
        $projectEntity->setStartAt(now());
        $projectEntity->setFinishAt(now());
        StatusValidationFactory::make($projectEntity)->validate();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_in_progress_strategy_from_factory_success_case(ProjectEntity $projectEntity)
    {
        $this->expectNotToPerformAssertions();
        $projectEntity->setStatus(StatusProjectEnum::IN_PROGRESS);
        $projectEntity->setStartAt(now());
        $projectEntity->setFinishAt(null);
        StatusValidationFactory::make($projectEntity)->validate();
    }
}
