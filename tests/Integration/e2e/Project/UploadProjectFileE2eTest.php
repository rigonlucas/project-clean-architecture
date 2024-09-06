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
            $content->file_name
        );

        // Assert
        $response->assertStatus(200);


        Storage::disk(config('filesystems.default'))
            ->assertExists($filePath);

        $this->assertDatabaseHas('project_files', [
            'uuid' => $content->uuid,
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid,
            'file_name' => $file->name,
            'file_path' => $filePath,
            'file_extension' => $file->extension(),
            'file_size' => $file->getSize(),
            //'file_type' => $content->file_type, do the validation into controller
            'status' => StatusFileEnum::FINISHED->value,
            'context' => ContextFileEnum::PROJECT->value,
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
