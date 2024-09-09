<?php

namespace Tests\Integration\e2e\Project;

use App\Models\Project;
use App\Models\User;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('test_api_delete_project_e2e')]
#[Group('test_e2e_project')]
#[Group('test_project')]
class ProjectDeleteSoftlyE2eTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function test_api_delete_project_softly()
    {
        $projectFactory = Project::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account_uuid
        ]);

        $response = $this->delete(route('api.v1.project.soft-delete', ['uuid' => $projectFactory->uuid]))
            ->assertStatus(ResponseStatus::OK->value)
            ->assertJsonStructure([
                'data' => [
                    'project_uuid',
                    'project_signature',
                    'deleted_relations',
                ]
            ]);

        $content = json_decode($response->content());

        $this->assertSoftDeleted(
            'projects',
            ['uuid' => $projectFactory->uuid, 'ulid_deletion' => $content->data->project_signature]
        );
        $this->assertEquals(
            [
                'project_cards',
                'project_files',
                'project_tasks',
                'projects',
            ],
            $content->data->deleted_relations
        );
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
