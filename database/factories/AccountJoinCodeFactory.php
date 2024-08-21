<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountJoinCode>
 */
class AccountJoinCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->word,
            'account_id' => Account::factory(),
            'user_id' => User::factory(),
            'expired_at' => now()->addDay()
        ];
    }
}
