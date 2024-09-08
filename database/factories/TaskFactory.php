<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'created_by_user_uuid' => User::factory(),
            'account_uuid' => Account::factory(),
        ];
    }
}
