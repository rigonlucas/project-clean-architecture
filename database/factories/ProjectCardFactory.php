<?php

namespace Database\Factories;

use App\Models\ProjectCard;
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
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
