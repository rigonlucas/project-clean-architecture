<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @group e2e_account_user_list
 */
class AccountUserListE2eTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function test_success_case_user_list_with_authenticated_admin()
    {
        $response = $this->getJson(route('api.v1.user.list'));

        $response->assertStatus(ResponseStatus::OK->value);
        $data = json_decode($response->content());
        $this->assertEquals(1, $data->total);
        $this->assertCount(1, $data->data);
        $this->assertFalse(EmailValueObject::isEmailSuppressed($data->data[0]->email));


        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'email',
                    'birthday',
                    'role',
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function test_success_case_user_list_with_authenticated_user_as_editor()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'role' => UserRoles::EDITOR,
            ]),
            ['*']
        );
        $response = $this->getJson(route('api.v1.user.list'));
        $data = json_decode($response->content());
        $this->assertCount(1, $data->data);
        $this->assertTrue(EmailValueObject::isNotEmailSuppressed($data->data[0]->email));


        $response->assertStatus(ResponseStatus::OK->value);
    }

    public function test_success_case_for_paginated_user_list_with_authenticated_admin()
    {
        User::factory()->count(15)->create([
            'account_id' => $this->user->account_id,
        ]);
        $response = $this->getJson(route('api.v1.user.list', ['page' => 2, 'per_page' => 5]));

        $response->assertStatus(ResponseStatus::OK->value);
        $data = json_decode($response->content());
        $this->assertEquals(16, $data->total);
        $this->assertCount(5, $data->data);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'email',
                    'birthday',
                    'role',
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function test_fail_case_user_list_with_authenticated_user_as_viewer()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'role' => UserRoles::VIEWER,
            ]),
            ['*']
        );
        $response = $this->getJson(route('api.v1.user.list'));

        $response->assertStatus(ResponseStatus::FORBIDDEN->value);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => UserRoles::ADMIN,
        ]);
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
    }
}
