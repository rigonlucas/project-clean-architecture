<?php

namespace Tests\Integration\e2e\Project;

use App\Models\Project;
use App\Models\User;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('test_api_update_project_e2e')]
#[Group('test_e2e_project')]
#[Group('test_project')]
class UpdateProjectE2eTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function test_api_create_project_success_without_dates()
    {
        $project = Project::factory()->create([
            'created_by_user_id' => $this->user->id,
            'account_uuid' => $this->user->account_uuid,
        ]);
        $response = $this->putJson(
            route('api.v1.project.update', $project->uuid),
            [
                'name' => 'Project Name',
                'description' => 'Project Description',
                'status' => StatusProjectEnum::ON_HOLD->value,
                'start_at' => now()->addDay()->format('Y-m-d'),
                'finish_at' => now()->addMonths(2)->format('Y-m-d'),
            ]
        );

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'uuid',
            ]
        ]);
        $this->assertDatabaseHas('projects', [
            'name' => 'Project Name',
            'description' => 'Project Description',
            'start_at' => now()->addDay()->startOfDay()->format('Y-m-d H:i:s'),
            'finish_at' => now()->addMonths(2)->startOfDay()->format('Y-m-d H:i:s'),
            'status' => StatusProjectEnum::ON_HOLD->value,
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
