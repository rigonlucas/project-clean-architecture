<?php

namespace Tests\Unit\User\Entity;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatusCodeEnum;
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
        $user = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN
        );

        $this->assertTrue($user->hasPermission(UserRoles::ADMIN));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));
        $this->assertTrue($user->hasPermission(GeneralPermissions::WRITE));
        $this->assertTrue($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertTrue($user->hasPermission(GeneralPermissions::DELETE));

        $this->assertFalse($user->hasNotPermission(UserRoles::ADMIN));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::READ));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::WRITE));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::EXECUTE));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::DELETE));
    }

    public function test_user_entity_roles_as_editor()
    {
        $user = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::EDITOR
        );

        $this->assertTrue($user->hasPermission(UserRoles::EDITOR));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));
        $this->assertTrue($user->hasPermission(GeneralPermissions::WRITE));

        $this->assertFalse($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::DELETE));


        $this->assertFalse($user->hasNotPermission(UserRoles::EDITOR));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::READ));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::WRITE));

        $this->assertTrue($user->hasNotPermission(GeneralPermissions::EXECUTE));
        $this->assertTrue($user->hasNotPermission(GeneralPermissions::DELETE));
    }


    public function test_user_entity_roles_as_viewer()
    {
        $user = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::VIEWER
        );

        $this->assertTrue($user->hasPermission(UserRoles::VIEWER));
        $this->assertTrue($user->hasPermission(GeneralPermissions::READ));

        $this->assertFalse($user->hasPermission(GeneralPermissions::WRITE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::EXECUTE));
        $this->assertFalse($user->hasPermission(GeneralPermissions::DELETE));


        $this->assertFalse($user->hasNotPermission(UserRoles::VIEWER));
        $this->assertFalse($user->hasNotPermission(GeneralPermissions::READ));

        $this->assertTrue($user->hasNotPermission(GeneralPermissions::WRITE));
        $this->assertTrue($user->hasNotPermission(GeneralPermissions::EXECUTE));
        $this->assertTrue($user->hasNotPermission(GeneralPermissions::DELETE));
    }

    public function test_user_entity_roles_as_invalid()
    {
        $this->expectException(InvalidRoleException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::INTERNAL_SERVER_ERROR->value);
        $this->expectExceptionMessage('Invalid role');

        $userEntity = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: 0
        );
        $userEntity->getRoleName();
    }
}
