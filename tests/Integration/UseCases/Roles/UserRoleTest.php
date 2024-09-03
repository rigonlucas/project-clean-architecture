<?php

namespace Tests\Integration\UseCases\Roles;

use Core\Support\Permissions\UserRoles;
use Tests\TestCase;

/**
 * @group test_user_role
 */
class UserRoleTest extends TestCase
{
    public function test_general_for_admin()
    {
        $roles = UserRoles::getPermissionsForRole(UserRoles::ADMIN);
        $this->assertEquals(
            [
                1 => 'READ',
                2 => 'WRITE',
                4 => 'DELETE',
                8 => 'EXECUTE',
                16 => 'UPLOAD_FILES',
            ],
            $roles
        );
    }

    public function test_general_for_editor()
    {
        $roles = UserRoles::getPermissionsForRole(UserRoles::EDITOR);
        $this->assertEquals(
            [
                1 => 'READ',
                2 => 'WRITE',
                16 => 'UPLOAD_FILES'
            ],
            $roles
        );
    }

    public function test_general_for_viewer()
    {
        $roles = UserRoles::getPermissionsForRole(UserRoles::VIEWER);
        $this->assertEquals(
            [
                1 => 'READ',
            ],
            $roles
        );
    }

    public function test_invalid_role()
    {
        $roles = UserRoles::getPermissionsForRole(0);
        $this->assertEquals([], $roles);
    }

    public function test_is_valid_role()
    {
        $this->assertTrue(UserRoles::isValidRole(UserRoles::ADMIN));
        $this->assertTrue(UserRoles::isValidRole(UserRoles::EDITOR));
        $this->assertTrue(UserRoles::isValidRole(UserRoles::VIEWER));
        $this->assertFalse(UserRoles::isValidRole(0));

        $this->assertFalse(UserRoles::isInvalidRole(UserRoles::ADMIN));
        $this->assertFalse(UserRoles::isInvalidRole(UserRoles::EDITOR));
        $this->assertFalse(UserRoles::isInvalidRole(UserRoles::VIEWER));
        $this->assertTrue(UserRoles::isInvalidRole(0));
    }
}
