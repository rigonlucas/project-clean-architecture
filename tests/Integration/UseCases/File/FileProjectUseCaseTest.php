<?php

namespace Tests\Integration\UseCases\File;

use App\Models\Project;
use Core\Application\Project\File\Upload\ProjectUploadFileUseCase;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Shared\Inputs\FiletInput;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\Enum\File\FileTypeEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\File\Command\FileProjectCommand;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('test_file_use_case')]
class FileProjectUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    private ProjectUploadFileUseCase $useCase;

    public function test_create_file_success(): void
    {
        // arrange
        $projectModel = Project::factory()->create();
        $input = new FiletInput(
            name: 'name',
            type: FileTypeEnum::DOCUMENT,
            size: new BytesValueObject(1000),
            extension: FileExtensionsEnum::DOCX,
            contextFile: FileContextEnum::PROJECT,
            uuid: Uuid::fromString($projectModel->uuid)
        );

        // act
        $fileEntity = $this->useCase->execute(
            projecFiletInput: $input,
            authUserEntity: UserEntity::forIdentify(
                id: $projectModel->createdByUser->id,
                uuid: Uuid::fromString($projectModel->createdByUser->uuid),
                role: UserRoles::ADMIN,
                accountUuid: Uuid::fromString($projectModel->account_uuid)
            )
        );

        // assert
        $this->assertNotNull($fileEntity);
        $this->assertDatabaseHas('project_files', [
            'uuid' => $fileEntity->getUuid()->toString(),
            'file_name' => $fileEntity->getName(),
            'file_type' => $fileEntity->getType()->value,
            'file_size' => $fileEntity->getSize()->getBytes(),
            'file_extension' => $fileEntity->getExtension(),
            'project_uuid' => $projectModel->uuid,
            'created_by_user_id' => $projectModel->created_by_user_id,
            'account_uuid' => $projectModel->account_uuid,
            'context' => $fileEntity->getContext()->value,
            'status' => $fileEntity->getStatus()->value,
        ]);

        $expectedPath = sprintf(
            '%s/%s/%s/%s',
            $projectModel->account_uuid,
            $fileEntity->getContext()->value,
            $fileEntity->getEntityUuid(),
            $fileEntity->getUlidFileName() . '.' . $fileEntity->getExtension()->value
        );

        $this->assertEquals($expectedPath, $fileEntity->getPath());
    }

    public function test_create_file_project_not_found(): void
    {
        // arrange
        $this->expectExceptionMessage('Project not found');
        $this->expectExceptionCode(404);
        $input = new FiletInput(
            name: 'name',
            type: FileTypeEnum::DOCUMENT,
            size: new BytesValueObject(1000),
            extension: FileExtensionsEnum::DOCX,
            contextFile: FileContextEnum::PROJECT,
            uuid: Uuid::uuid4()
        );

        // act
        $this->useCase->execute(
            projecFiletInput: $input,
            authUserEntity: UserEntity::forIdentify(
                id: 1,
                uuid: Uuid::uuid4(),
                role: UserRoles::ADMIN,
                accountUuid: Uuid::uuid4()
            )
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new ProjectUploadFileUseCase(
            $this->app->make(FrameworkContract::class),
            new FileProjectCommand(),
            $this->app->make(ProjectMapperInterface::class)
        );
    }
}
