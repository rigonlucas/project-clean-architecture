<?php

namespace Tests\Feature\Models;

use App\Models\Project;
use App\Models\ProjectCard;
use App\Models\ProjectFile;
use App\Models\ProjectTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('test_project_relations')]
#[Group('test_models')]
class ProjectRelationsTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function test_project_has_many_tasks_through_project_tasks()
    {
        $project = Project::factory()->create([
            'account_uuid' => $this->user->account->uuid,
        ]);
        $task = Task::factory()->create([
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account->uuid,
        ]);

        ProjectTask::factory()->create([
            'project_uuid' => $project->uuid,
            'task_uuid' => $task->uuid,
            'created_by_user_uuid' => $this->user->uuid,
        ]);

        $projectFirst = Project::query()
            ->where('uuid', '=', $project->uuid)
            ->first();
        $tasks = $projectFirst->tasks;
        $this->assertNotEmpty($tasks);
        $this->assertEquals(1, $tasks->count());
        $this->assertEquals($task->uuid, $tasks->first()->uuid);
    }

    public function test_project_has_many_files()
    {
        $project = Project::factory()->create([
            'account_uuid' => $this->user->account->uuid,
        ]);
        $projectFile = ProjectFile::factory()->count(2)->create([
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
            'account_uuid' => $this->user->account->uuid,
        ]);

        $projectFirst = Project::query()
            ->where('uuid', '=', $project->uuid)
            ->first();
        $files = $projectFirst->files;
        $this->assertNotEmpty($files);
        $this->assertEquals(2, $files->count());
        $this->assertEquals($projectFile->first()->uuid, $files->first()->uuid);
    }

    public function test_project_has_manay_cards()
    {
        $project = Project::factory()->create([
            'account_uuid' => $this->user->account->uuid,
        ]);
        $projectCard = ProjectCard::factory()->count(2)->create([
            'project_uuid' => $project->uuid,
            'created_by_user_uuid' => $this->user->uuid,
        ]);

        $projectFirst = Project::query()
            ->where('uuid', '=', $project->uuid)
            ->first();
        $cards = $projectFirst->cards;
        $this->assertNotEmpty($cards);
        $this->assertEquals(2, $cards->count());
        $this->assertEquals($projectCard->first()->uuid, $cards->first()->uuid);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
