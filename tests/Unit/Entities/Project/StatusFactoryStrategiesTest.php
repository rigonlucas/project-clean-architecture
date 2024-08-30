<?php

namespace Tests\Unit\Entities\Project;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\StatusValidationFactory;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
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
                    1,
                    1,
                    1,
                    user: UserEntity::forDetail(
                        id: 1,
                        name: 1,
                        email: 1,
                        uuid: Uuid::uuid7(),
                        account: AccountEntity::forIdentify(1),
                        role: UserRoles::ADMIN
                    ),
                    account: AccountEntity::forIdentify(1),
                    uuid: Uuid::uuid7(),
                    status: StatusProjectEnum::IN_PROGRESS,
                    finishAt: now()
                )
            ]
        ];
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_default_strategy_from_factory(ProjectEntity $projectEntity)
    {
        $this->expectNotToPerformAssertions();
        $projectEntity->setStatus(StatusProjectEnum::BACKLOG);
        StatusValidationFactory::make($projectEntity)->validateWithThrowException();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_pending_strategy_from_factory(projectentity $projectEntity)
    {
        $this->expectNotToPerformAssertions();
        $projectEntity->setStatus(StatusProjectEnum::PENDING);
        StatusValidationFactory::make($projectEntity)->validateWithThrowException();
    }

    /**
     * @dataProvider projectEntityProvider
     */
    public function test_in_progress_strategy_from_factory(ProjectEntity $projectEntity)
    {
        $this->expectException(DateRequiredException::class);
        $projectEntity->setStatus(StatusProjectEnum::IN_PROGRESS);
        StatusValidationFactory::make($projectEntity)->validateWithThrowException();
    }
}
