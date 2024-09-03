<?php

namespace Tests\Unit\Entities\Project;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\ProjectFile\ProjectFileEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group project_file_test
 */
class ProjectFileEntityTest extends TestCase
{
    public static function projectFileContextProvider(): array
    {
        foreach (ProjectFileContextEnum::cases() as $context) {
            $data[strtolower($context->value)] = [
                $context
            ];
        }
        return $data;
    }

    /**
     * @dataProvider projectFileContextProvider
     */
    public function test_entity_must_generate_path_storage_for_file_context(ProjectFileContextEnum $context): void
    {
        $userUuid = Uuid::uuid7();
        $accountUuid = Uuid::uuid7();
        $projectUuid = Uuid::uuid7();
        $userEntity = UserEntity::forIdentify(
            uuid: $userUuid,
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );

        $projectFileEntity = ProjectFileEntity::forCreate(
            Uuid::uuid7(),
            'Name',
            TypeFileEnum::AUDIO,
            new BytesValueObject(2),
            AllowedExtensionsEnum::CSV,
            projectEntity: ProjectEntity::forCreate(
                name: 'Nome',
                description: 'Desc',
                user: $userEntity,
                account: $userEntity->getAccount(),
                uuid: $projectUuid,
                status: StatusProjectEnum::IN_PROGRESS
            ),
            userEntity: $userEntity,
            context: $context
        );
        $projectFileEntity->applyPathMask();
        $this->assertEquals(
            sprintf(
                '%s/%s/%s.%s',
                $accountUuid,
                strtolower($context->value),
                $projectFileEntity->getUuid()->toString(),
                AllowedExtensionsEnum::CSV->value
            ),
            $projectFileEntity->getPath()
        );
    }
}
