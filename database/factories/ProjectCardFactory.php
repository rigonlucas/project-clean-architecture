<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectCard>
 */
class ProjectCardFactory extends Factory
{
    protected $model = ProjectCard::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'created_by_user_id' => User::factory(),
            'project_uuid' => Project::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
