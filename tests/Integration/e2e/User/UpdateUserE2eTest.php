<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Support\Http\HttpApiHeaders;
use Core\Support\Http\ResponseStatusCodeEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @group e2e_update_user
 */
class UpdateUserE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private User $user;

    public function test_update_user_success_case()
    {
        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->user->uuid]),
            [
                'name' => $this->faker->name . 'updated',
                'email' => $this->faker->userName . 'email@gmail.com',
                'password' => 'teste12345',
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );

        //assert response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'uuid'
            ]
        ]);
    }

    public function test_update_user_fail_case_email_exists_and_birthdate_less_than_18_years_old()
    {
        //create user with same email
        $userMoodel = User::factory()->create([
            'email' => $this->faker->userName . '@gmail.com'
        ]);

        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->user->uuid]),
            [
                'name' => $userMoodel->name,
                'email' => $userMoodel->email,
                'password' => 'teste1234',
                'birthday' => now()->subYears(17)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'birthday'
            ]
        ]);
    }

    public function test_update_user_fail_case_password_less_than_8_characters()
    {
        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->user->uuid]),
            [
                'name' => $this->faker->name,
                'email' => $this->faker->userName . '@gmail.com',
                'password' => $this->faker->text(7),
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );

        //assert response
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
    }

    public function test_update_user_fail_case_email_exists()
    {
        //create user with same email
        $otherUser = User::factory()->create([
            'email' => $this->faker->userName . '@gmail.com'
        ]);

        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->user->uuid]),
            [
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'password' => 'teste12345',
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );

        //assert response
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);


        $response->assertJsonFragment([
            'email' => ['Email já utilizado por outro usuário']
        ]);
    }

    public function test_update_user_success_case_new_valid_email()
    {
        //create user with same email
        $otherUser = User::factory()->create([
            'email' => $this->faker->userName . '@gmail.com'
        ]);

        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->user->uuid]),
            [
                'name' => $otherUser->name,
                'email' => $this->faker->userName . '@gmail.com',
                'password' => 'teste12345',
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'uuid'
            ]
        ]);
    }

    public function test_update_user_fail_case_user_not_found()
    {
        //update user
        $response = $this->putJson(
            route('api.v1.user.update', ['uuid' => $this->faker->uuid]),
            [
                'name' => $this->faker->name,
                'email' => $this->faker->userName . '@gmail.com',
                'password' => 'teste12345',
                'birthday' => now()->subYears(18)->format('Y-m-d')
            ],
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(ResponseStatusCodeEnum::NOT_FOUND->value);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
    }
}
