<?php

namespace Tests\Integration\e2e\Project\File;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\ValueObjects\File\DefaultPathValueObject;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Uid\Ulid;
use Tests\TestCase;

#[Group('test_api_e2e_delete_project_file')]
#[Group('test_e2e_project')]
#[Group('test_project')]
class ProjectDeleteFileE2eTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function test_api_delete_project_file_success(): void
    {
        // Arrange
        $project = Project::factory()->create([
            'created_by_user_id' => $this->user->id,
            'account_uuid' => $this->user->account_uuid
        ]);
        $uliFileName = Ulid::generate();
        $caminho = (new DefaultPathValueObject())
            ->addPathSegment($project->account_uuid)
            ->addPathSegment(FileContextEnum::PROJECT->value)
            ->addPathSegment($project->uuid)
            ->addPathSegment($uliFileName)
            ->addPathSegment(FileExtensionsEnum::TXT->value)
            ->apply();
        $file = ProjectFile::factory()->create([
            'project_uuid' => $project->uuid,
            'created_by_user_id' => $this->user->id,
            'account_uuid' => $this->user->account_uuid,
            'context' => FileContextEnum::PROJECT->value,
            'file_extension' => FileExtensionsEnum::TXT->value,
            'file_path' => $caminho->getPath()
        ]);
        Storage::fake(config('filesystems.default'));
        $directory = dirname($caminho->getPath());
        $baseName = basename($caminho->getPath());
        Storage::putFileAs(
            $directory,
            UploadedFile::fake()->createWithContent(
                name: 'document.txt',
                content: 'content'
            ),
            $baseName
        );

        // Act
        $this->deleteJson(route('api.v1.project.file.delete', [
            'projectUuid' => $project->uuid,
            'fileUuid' => $file->uuid,
        ]));

        // Assert
        $this->assertDatabaseMissing('project_files', [
            'uuid' => $file->uuid
        ]);
        Storage::assertMissing($caminho->getPath());
    }

    public function test_api_delete_project_file_must_not_delete_file_not_found(): void
    {
        // Arrange
        $project = Project::factory()->create([
            'created_by_user_id' => $this->user->id,
            'account_uuid' => $this->user->account_uuid
        ]);
        $uliFileName = Ulid::generate();
        $caminho = (new DefaultPathValueObject())
            ->addPathSegment($project->account_uuid)
            ->addPathSegment(FileContextEnum::PROJECT->value)
            ->addPathSegment($project->uuid)
            ->addPathSegment($uliFileName)
            ->addPathSegment(FileExtensionsEnum::TXT->value)
            ->apply();
        $file = ProjectFile::factory()->create([
            'project_uuid' => $project->uuid,
            'created_by_user_id' => $this->user->id,
            'account_uuid' => $this->user->account_uuid,
            'context' => FileContextEnum::PROJECT->value,
            'file_extension' => FileExtensionsEnum::TXT->value,
            'file_path' => $caminho->getPath()
        ]);

        $directory = dirname($caminho->getPath());
        $baseName = basename($caminho->getPath());

        Storage::fake(config('filesystems.default'));
        Storage::putFileAs(
            $directory,
            UploadedFile::fake()->createWithContent(
                name: 'document.txt',
                content: 'content'
            ),
            $baseName
        );
        Storage::shouldReceive('disk')
            ->andReturnSelf();
        Storage::shouldReceive('delete')
            ->andReturn(false);
        Storage::shouldReceive('exists')
            ->andReturn(true);

        // Act
        $response = $this->deleteJson(route('api.v1.project.file.delete', [
            'projectUuid' => $project->uuid,
            'fileUuid' => $file->uuid,
        ]));
        $response->assertStatus(ResponseStatus::INTERNAL_SERVER_ERROR->value);

        // Assert
        $this->assertDatabaseHas('project_files', [
            'uuid' => $file->uuid
        ]);
        Storage::disk(config('filesystems.default'))->exists($caminho->getPath());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        $this->actingAs($this->user);
    }
}
