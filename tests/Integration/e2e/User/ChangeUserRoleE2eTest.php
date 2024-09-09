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

#[Group('e2e_change_user_role')]
class ChangeUserRoleE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function test_api_success_case_for_change_role_from_any_user_when_authenticated_user_is_admin(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER,
                'account_uuid' => $userAuth->account_uuid
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::NO_CONTENT->value);
    }

    public function test_api_success_case_for_change_role_when_is_the_same_already_defined(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::ADMIN,
                'account_uuid' => $userAuth->account_uuid
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::NO_CONTENT->value);
    }

    public function test_api_fail_case_for_change_role_from_any_user_when_authenticated_user_as_viewer_admin(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::VIEWER
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::FORBIDDEN->value);
    }

    public function test_api_fail_case_for_change_role_from_any_user_when_authenticated_user_as_editor_admin(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::EDITOR
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::FORBIDDEN->value);
    }

    public function test_api_fail_role_field_is_required(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::UNPROCESSABLE_ENTITY->value);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'role'
            ]
        ]);
    }

    public function test_api_fail_when_user_not_found(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $this->faker->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::NOT_FOUND->value);
    }

    public function test_api_tail_if_users_arent_from_same_account(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER
            ]
        );
        $response = $this->patchJson(
            route('api.v1.user.change-role', ['uuid' => $userToChange->uuid]),
            [
                'role' => UserRoles::ADMIN,
            ],
            HttpApiHeaders::$headersJson
        );
        $response->assertStatus(ResponseStatus::FORBIDDEN->value);
    }
}
