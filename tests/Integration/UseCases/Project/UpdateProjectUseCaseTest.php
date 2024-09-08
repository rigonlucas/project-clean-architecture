<?php

namespace Tests\Integration\UseCases\Project;

use App\Models\Project;
use App\Models\User;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Project\Update\inputs\UpdateProjectInput;
use Core\Application\Project\Update\UpdateProjectUseCase;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group("test_update_project_use_case")]
#[Group('test_project')]
class UpdateProjectUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    private UpdateProjectUseCase $useCase;
    private User $user;

    public function test_create_project_success_without_dates()
    {
        $project = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid,
        ]);
        $input = new UpdateProjectInput(
            uuid: Uuid::fromString($project->uuid),
            name: 'Nome',
            description: 'Desc',
            startAt: null,
            finishAt: null,
            status: StatusProjectEnum::PENDING
        );
        $projectEntity = $this->useCase->execute(
            createProjectInput: $input,
            authUser: UserEntity::forIdentify(
                uuid: Uuid::fromString($this->user->uuid),
                role: UserRoles::ADMIN,
                accountUuid: Uuid::fromString($this->user->account_uuid)
            )
        );

        $this->assertDatabaseHas('projects', [
            'uuid' => $projectEntity->getUuid()->toString(),
            'name' => 'Nome',
            'description' => 'Desc',
            'start_at' => null,
            'finish_at' => null
        ]);
    }


    public function test_create_project_success_with_dates()
    {
        $project = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid,
        ]);
        $input = new UpdateProjectInput(
            uuid: Uuid::fromString($project->uuid),
            name: 'Nome',
            description: 'Desc',
            startAt: now(),
            finishAt: now()->addYears(),
            status: StatusProjectEnum::ON_HOLD
        );
        $projectEntity = $this->useCase->execute(
            createProjectInput: $input,
            authUser: UserEntity::forIdentify(
                uuid: Uuid::fromString($this->user->uuid),
                role: UserRoles::ADMIN,
                accountUuid: Uuid::fromString($this->user->account_uuid)
            )
        );

        $this->assertDatabaseHas('projects', [
            'uuid' => $projectEntity->getUuid()->toString(),
            'name' => 'Nome',
            'description' => 'Desc',
            'start_at' => $input->startAt,
            'finish_at' => $input->finishAt
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new UpdateProjectUseCase(
            $this->app->make(ProjectCommandInterface::class),
            $this->app->make(ProjectMapperInterface::class)
        );
        $this->user = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
    }
}
