<?php

namespace Tests\Unit\User\Entity;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Permissions\Access\GeneralPermissions;
use Core\Support\Permissions\Access\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group UserEntityRolesTest
 */
class UserEntityRolesTest extends TestCase
{
    public function test_user_entity_roles_as_admin()
    {
        $user = UserEntity::forDetail(
            id: 1,
            name: 'John Doe',
            email: 'email@email.com',
            uuid: Uuid::uuid7(),
            account: AccountEntity::forIdentify(1),
            role: UserRoles::ADMIN
        );

        $this->assertTrue($user->hasRolePermission(UserRoles::ADMIN));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));
        $this->assertTrue($user->hasPermission(GeneralPermissions::WRITE));
        $this->assertTrue($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertTrue($user->hasPermission(GeneralPermissions::DELETE));
    }

    public function test_user_entity_roles_as_editor()
    {
        $user = UserEntity::forDetail(
            id: 1,
            name: 'John Doe',
            email: 'email@email.com',
            uuid: Uuid::uuid7(),
            account: AccountEntity::forIdentify(1),
            role: UserRoles::EDITOR
        );

        $this->assertTrue($user->hasRolePermission(UserRoles::EDITOR));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));
        $this->assertTrue($user->hasPermission(GeneralPermissions::WRITE));

        $this->assertFalse($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::DELETE));
    }


    public function test_user_entity_roles_as_viewer()
    {
        $user = UserEntity::forDetail(
            id: 1,
            name: 'John Doe',
            email: 'email@email.com',
            uuid: Uuid::uuid7(),
            account: AccountEntity::forIdentify(1),
            role: UserRoles::VIEWER
        );

        $this->assertTrue($user->hasRolePermission(UserRoles::VIEWER));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));

        $this->assertFalse($user->hasPermission(GeneralPermissions::WRITE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::DELETE));
    }
}
