<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @group relations
 */
class RelationsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $user = User::factory()->create();
        AccountUser::factory()->create([
            'user_id' => $user->id,
        ]);
        $user->loadMissing('accounts');
        dd($user->accounts->first()->usersWithPivotData->first()->toArray());
        dd($user);
    }*/
}
