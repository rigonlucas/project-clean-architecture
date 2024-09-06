<?php

namespace Tests\Integration\e2e\Project;

use App\Models\Project;
use App\Models\User;
use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('test_e2e_upload_project_file')]
class UploadProjectFileE2eTest extends TestCase
{
    use DatabaseMigrations;

    public function test_must_upload_file_to_project_and_check_if_was_uploaded(): void
    {
        // Arrange
        Storage::fake(config('filesystems.default'));
        $project = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid
        ]);
        $file = UploadedFile::fake()->create(
            'document.pdf',
            100,
            'application/pdf'
        );

        // Act
        $response = $this->postJson(
            route('api.v1.project.upload-file', ['uuid' => $project->uuid]),
            [
                'project_uuid' => $project->uuid,
                'file' => $file
            ]
        );
        $content = json_decode($response->content());
        $filePath = sprintf(
            '%s/%s/%s/%s',
            $project->account_uuid,
            ContextFileEnum::PROJECT->value,
            $project->uuid,
            $content->data->file_name
        );

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'project_uuid',
                'file_name'
            ]
        ]);


        Storage::disk(config('filesystems.default'))
            ->assertExists($filePath);

        $this->assertDatabaseHas('project_files', [
            'uuid' => $content->data->uuid,
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid,
            'file_name' => $file->name,
            'file_path' => $filePath,
            'file_extension' => $file->extension(),
            'file_size' => $file->getSize(),
            //'file_type' => $content->data->file_type, do the validation into controller
            'status' => StatusFileEnum::FINISHED->value,
            'context' => ContextFileEnum::PROJECT->value,
        ]);
    }

    public function test_must_return_error_when_upload_file_failed_after_upload_success(): void
    {
        // Arrange
        Storage::shouldReceive('disk')
            ->andReturnSelf();

        Storage::shouldReceive('putFileAs')
            ->andReturn('');

        Storage::shouldReceive('exists')
            ->andReturn(false);

        $project = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid
        ]);
        $file = UploadedFile::fake()->create(
            'document.pdf',
            100,
            'application/pdf'
        );

        // Act
        $response = $this->postJson(
            route('api.v1.project.upload-file', ['uuid' => $project->uuid]),
            [
                'project_uuid' => $project->uuid,
                'file' => $file
            ]
        );
        // Assert
        $response->assertStatus(500);
        $this->assertDatabaseMissing('project_files', [
            'project_uuid' => $project->uuid,
        ]);
    }

    public function test_must_return_error_when_upload_file_failed_on_save(): void
    {
        // Arrange
        Storage::shouldReceive('disk')
            ->andReturnSelf();

        Storage::shouldReceive('putFileAs')
            ->andReturn(false);

        $project = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid
        ]);
        $file = UploadedFile::fake()->create(
            'document.pdf',
            100,
            'application/pdf'
        );

        // Act
        $response = $this->postJson(
            route('api.v1.project.upload-file', ['uuid' => $project->uuid]),
            [
                'project_uuid' => $project->uuid,
                'file' => $file
            ]
        );
        // Assert
        $response->assertStatus(500);
        $this->assertDatabaseMissing('project_files', [
            'project_uuid' => $project->uuid,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => UserRoles::ADMIN,
        ]);
        $this->actingAs($this->user);
    }
}
