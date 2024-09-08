<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectTask>
 */
class ProjectTaskFactory extends Factory
{
    protected $model = ProjectTask::class;

    public function definition()
    {
        return [
            'project_uuid' => Project::factory(),
            'task_uuid' => Task::factory(),
            'created_by_user_uuid' => User::factory(),
        ];
    }
}
