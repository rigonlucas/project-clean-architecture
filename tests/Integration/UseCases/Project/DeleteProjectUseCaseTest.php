<?php

namespace Tests\Integration\UseCases\Project;

use App\Models\Project;
use App\Models\ProjectCard;
use App\Models\ProjectFile;
use App\Models\ProjectTask;
use App\Models\Task;
use App\Models\User;
use Core\Application\Project\Delete\DeleteProjectUseCase;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\FileStatusEnum;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group("test_delete_project_use_case")]
#[Group('test_project')]
class DeleteProjectUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private UserEntity $userEntity;
    private DeleteProjectUseCase $useCase;

    public function test_delete_project_with_tasks(): void
    {
        // Arrange
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account->uuid,
        ]);

        $projectTasks = ProjectTask::factory()->count(2)->create([
            'project_uuid' => $project->uuid,
            'task_uuid' => $task->uuid,
            'created_by_user_uuid' => $this->user->uuid,
        ]);
        // Act
        $projectAggregate = $this->useCase->execute($project->uuid, $this->userEntity);

        // Assert
        $this->assertSoftDeleted($project);
        $this->assertDatabaseHas('projects', [
            'uuid' => $project->uuid->toString(),
            'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
        ]);
        foreach ($projectTasks as $projectTask) {
            $projectTask->refresh();
            $this->assertSoftDeleted($projectTask);
            $this->assertDatabaseHas('project_tasks', [
                'task_uuid' => $projectTask->task_uuid,
                'project_uuid' => $project->uuid->toString(),
                'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
            ]);
        }
    }

    public function test_delete_project_with_files(): void
    {
        // Arrange
        $project = Project::factory()->create([
            'account_uuid' => $this->user->account->uuid,
        ]);
        $projectFile = ProjectFile::factory()->count(2)->create([
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account->uuid,
        ]);

        // Act
        $projectAggregate = $this->useCase->execute($project->uuid, $this->userEntity);

        // Assert
        $this->assertSoftDeleted($project);
        $this->assertDatabaseHas('projects', [
            'uuid' => $project->uuid->toString(),
            'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
        ]);
        foreach ($projectFile as $file) {
            $file->refresh();
            $this->assertSoftDeleted($file);
            $this->assertEquals(FileStatusEnum::SOFT_DELETED, FileStatusEnum::from($file->status));
            $this->assertDatabaseHas('project_files', [
                'project_uuid' => $project->uuid->toString(),
                'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
            ]);
        }
    }

    public function test_delete_project_with_cards(): void
    {
        // Arrange
        $project = Project::factory()->create([
            'account_uuid' => $this->user->account->uuid,
        ]);
        $projectCard = ProjectCard::factory()->count(2)->create([
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
        ]);

        // Act
        $projectAggregate = $this->useCase->execute($project->uuid, $this->userEntity);

        // Assert
        $this->assertSoftDeleted($project);
        $this->assertDatabaseHas('projects', [
            'uuid' => $project->uuid->toString(),
            'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
        ]);
        foreach ($projectCard as $card) {
            $card->refresh();
            $this->assertSoftDeleted($card);
            $this->assertDatabaseHas('project_cards', [
                'project_uuid' => $project->uuid->toString(),
                'ulid_deletion' => $projectAggregate->projectEntity->getUlidDeletion(),
            ]);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->userEntity = UserEntity::forIdentify(
            uuid: Uuid::fromString($this->user->uuid),
            role: UserRoles::ADMIN,
            accountUuid: Uuid::fromString($this->user->account_uuid)
        );
        $this->useCase = new DeleteProjectUseCase(
            $this->app->make(FrameworkContract::class),
            $this->app->make(ProjectCommandInterface::class),
            $this->app->make(ProjectMapperInterface::class)
        );
    }
}
