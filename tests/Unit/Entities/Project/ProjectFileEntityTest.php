<?php

namespace Tests\Unit\Entities\Project;

use Core\Domain\Entities\File\Root\FileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Support\Permissions\UserRoles;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('project_file_test')]
class ProjectFileEntityTest extends TestCase
{
    public static function projectFileContextProvider(): array
    {
        foreach (FileContextEnum::cases() as $context) {
            $data[strtolower($context->value)] = [
                $context
            ];
        }
        return $data;
    }

    /**
     * @dataProvider projectFileContextProvider
     */
    public function test_entity_must_generate_path_storage_for_file_context(FileContextEnum $context): void
    {
        $userUuid = Uuid::uuid7();
        $accountUuid = Uuid::uuid7();
        $userEntity = UserEntity::forIdentify(
            uuid: $userUuid,
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );

        $projectFileEntity = FileEntity::forCreate(
            uuid: Uuid::uuid7(),
            name: 'Name',
            type: TypeFileEnum::AUDIO,
            size: new BytesValueObject(2),
            extension: AllowedExtensionsEnum::CSV,
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
