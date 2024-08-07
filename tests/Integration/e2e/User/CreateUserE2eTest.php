<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Tools\Http\HttpApiHeaders;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group e2e_create_user
 */
class CreateUserE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function test_create_user_success_case()
    {
        $response = $this->postJson(
            route('v1.user.create'),
            [
                'name' => $this->faker->name,
                'email' => $this->faker->userName . '@gmail.com',
                'password' => $this->faker->password(8),
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'uuid',
                'name',
                'email',
                'birthday',
            ]
        ]);
    }

    public function test_create_user_fail_case_email_exists_and_birthdate_less_than_18_years_old()
    {
        $userMoodel = User::factory()->create([
            'email' => $this->faker->userName . '@gmail.com'
        ]);
        $response = $this->postJson(
            route('v1.user.create'),
            [
                'name' => $userMoodel->name,
                'email' => $userMoodel->email,
                'password' => $this->faker->password(8),
                'birthday' => now()->subYears(17)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'birthday',
                'email'
            ]
        ]);
    }

    public function test_create_user_fail_case_password_less_than_8_characters()
    {
        $response = $this->postJson(
            route('v1.user.create'),
            [
                'name' => $this->faker->name,
                'email' => $this->faker->userName . '@gmail.com',
                'password' => $this->faker->words(7),
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
    }
}
