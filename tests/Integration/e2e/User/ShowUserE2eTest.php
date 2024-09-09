<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Support\Http\HttpApiHeaders;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('e2e_show_user')]
class ShowUserE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private User $user;

    public function test_api_show_user_success_case_with_same_user_auth()
    {
        //update user
        $response = $this->getJson(
            route('api.v1.user.show', ['uuid' => $this->user->uuid]),
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(ResponseStatus::OK->value);
        $response->assertJsonStructure([
            'data' => [
                'uuid'
            ]
        ]);
    }

    public function test_api_show_forbiden_case_with_other_user_auth_must_block_access()
    {
        $user = User::factory()->create();

        //update user
        $response = $this->getJson(
            route('api.v1.user.show', ['uuid' => $user->uuid]),
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(403);
    }

    public function test_api_show_user_success_access_for_auth_user_as_an_admin()
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            User::factory()->create([
                'role' => UserRoles::ADMIN
            ]),
            ['*']
        );

        //update user
        $response = $this->getJson(
            route('api.v1.user.show', ['uuid' => $user->uuid]),
            HttpApiHeaders::$headersJson
        );
        //assert response
        $response->assertStatus(ResponseStatus::OK->value);
        $response->assertJsonStructure([
            'data' => [
                'uuid'
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
