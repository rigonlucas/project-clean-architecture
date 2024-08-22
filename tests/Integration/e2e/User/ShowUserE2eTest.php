<?php

namespace Tests\Integration\e2e\User;

use App\Models\User;
use Core\Support\Http\HttpApiHeaders;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @group e2e_show_user
 */
class ShowUserE2eTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private User $user;

    public function test_update_user_success_case()
    {
        //update user
        $response = $this->getJson(
            route('v1.user.show', ['uuid' => $this->user->uuid]),
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
