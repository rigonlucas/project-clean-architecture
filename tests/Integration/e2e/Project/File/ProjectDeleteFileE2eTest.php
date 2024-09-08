<?php

namespace Tests\Integration\e2e\Project\File;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('test_e2e_delete_project_file')]
#[Group('test_e2e_project')]
#[Group('test_project')]
class ProjectDeleteFileE2eTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
