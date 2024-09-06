<?php

namespace Database\Factories;

use App\Models\ProjectCard;
use App\Models\ProjectTask;
use App\Models\Task;
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
            'project_id' => ProjectCard::factory(),
            'task_id' => Task::factory(),
        ];
    }
}
