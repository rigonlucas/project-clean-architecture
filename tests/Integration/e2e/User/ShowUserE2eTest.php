<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Tools\Http\HttpApiHeaders;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @group e2e_update_user
 */
class ShowUserE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private User $user;

    public function test_update_user_success_case()
    {
        //update user
        $response = $this->putJson(
            route('v1.user.update'),
            [
                'uuid' => $this->user->uuid,
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
                'uuid',
                'name',
                'email',
                'birthday',
            ]
        ]);
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
