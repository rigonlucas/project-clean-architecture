<?php

namespace Tests\Integration\UseCases\Project;

use App\Models\User;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Create\CreateProjectUseCase;
use Core\Application\Project\Create\inputs\CreateProjectInput;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group test_create_project_use_case
 */
class CreateProjectUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    private CreateProjectUseCase $useCase;
    private User $user;

    public function test_create_project_success_without_dates()
    {
        $input = new CreateProjectInput(
            name: 'Nome',
            description: 'Desc',
            startAt: null,
            finishAt: null
        );
        $projectEntity = $this->useCase->execute(
            createProjectInput: $input,
            authUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid),
                role: UserRoles::ADMIN,
                accountId: $this->user->account_id
            )
        );

        $this->assertDatabaseHas('projects', [
            'id' => $projectEntity->getId(),
            'uuid' => $projectEntity->getUuid()->toString(),
            'name' => 'Nome',
            'description' => 'Desc',
            'start_at' => null,
            'finish_at' => null
        ]);
    }


    public function test_create_project_success_with_dates()
    {
        $input = new CreateProjectInput(
            name: 'Nome',
            description: 'Desc',
            startAt: now(),
            finishAt: now()->addYears()
        );
        $projectEntity = $this->useCase->execute(
            createProjectInput: $input,
            authUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid),
                role: UserRoles::ADMIN,
                accountId: $this->user->account_id
            )
        );

        $this->assertDatabaseHas('projects', [
            'id' => $projectEntity->getId(),
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
        $this->useCase = new CreateProjectUseCase(
            $this->app->make(FrameworkContract::class),
            $this->app->make(ProjectCommandInterface::class),
            $this->app->make(ProjectMapperInterface::class)
        );
        $this->user = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
    }
}
