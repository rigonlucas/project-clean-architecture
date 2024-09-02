<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

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
            'uuid' => Uuid::uuid7()->toString(),
            'code' => $this->faker->unique()->word,
            'account_uuid' => Account::factory(),
            'user_uuid' => User::factory(),
            'expired_at' => now()->addDay()
        ];
    }
}
