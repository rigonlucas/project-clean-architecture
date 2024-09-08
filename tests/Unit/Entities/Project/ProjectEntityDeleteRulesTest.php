<?php

namespace Tests\Unit\Entities\Project;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Permissions\UserRoles;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('project_entity_delete_rules')]
#[Group('test_project')]
class ProjectEntityDeleteRulesTest extends TestCase
{
    public function test_project_entity_delete_rules_as_admin(): void
    {
        // Arrange
        $this->expectNotToPerformAssertions();

        // Act
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
        $projectEntity->canDeleteProject();
    }

    public function test_must_throw_exception_project_entity_delete_rules_as_an_editor(): void
    {
        // Arrange
        $this->expectException(ForbidenException::class);
        $this->expectExceptionMessage('You do not have permission to delete a project');

        // Act
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::EDITOR,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
        );
        $projectEntity->canDeleteProject();
    }

    public function test_must_throw_exception_project_entity_delete_rules_as_an_viewer(): void
    {
        // Arrange
        $this->expectException(ForbidenException::class);
        $this->expectExceptionMessage('You do not have permission to delete a project');

        // Act
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forCreate(
            name: 'Project Name',
            description: 'Project Description',
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::VIEWER,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify($accountUuid),
            uuid: Uuid::uuid7(),
            status: StatusProjectEnum::BACKLOG,
        );
        $projectEntity->canDeleteProject();
    }

    public function test_must_throw_exception_project_entity_delete_when_an_user_is_not_informed(): void
    {
        // Arrange
        $this->expectException(ForbidenException::class);
        $this->expectExceptionMessage('An user is required to delete a project');

        // Act
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forIdentify(
            uuid: Uuid::uuid7(),
            user: null,
            account: AccountEntity::forIdentify($accountUuid),
        );
        $projectEntity->canDeleteProject();
    }

    public function test_must_throw_exception_project_entity_delete_when_an_account_is_not_informed(): void
    {
        // Arrange
        $this->expectException(ForbidenException::class);
        $this->expectExceptionMessage('An account is required to delete a project');

        // Act
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forIdentify(
            uuid: Uuid::uuid7(),
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: null,
        );
        $projectEntity->canDeleteProject();
    }

    public function test_must_throw_exception_project_entity_delete_when_an_account_is_different_of_owner(): void
    {
        // Arrange
        $this->expectException(ForbidenException::class);
        $this->expectExceptionMessage('Your account is not allowed to delete a project in this account');

        // Act
        $accountUuid = Uuid::uuid7();
        $projectEntity = ProjectEntity::forIdentify(
            uuid: Uuid::uuid7(),
            user: UserEntity::forIdentify(
                uuid: Uuid::uuid7(),
                role: UserRoles::ADMIN,
                accountUuid: $accountUuid
            ),
            account: AccountEntity::forIdentify(Uuid::uuid7()),
        );
        $projectEntity->canDeleteProject();
    }
}
