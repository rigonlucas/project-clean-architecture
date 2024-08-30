<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'start_at' => $this->faker->dateTime,
            'finish_at' => $this->faker->dateTime,
            'uuid' => Uuid::uuid7()->toString(),
            'created_by_user_id' => User::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
