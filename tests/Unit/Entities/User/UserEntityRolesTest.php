<?php

namespace Tests\Unit\Entities\User;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Exceptions\InvalideRules\InvalidRoleException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\Access\GeneralPermissions;
use Core\Support\Permissions\UserRoles;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('UserEntityRolesTest')]
class UserEntityRolesTest extends TestCase
{
    public function test_user_entity_roles_as_admin()
    {
        $user = UserEntity::forIdentify(
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
        $this->expectExceptionCode(ResponseStatus::INTERNAL_SERVER_ERROR->value);
        $this->expectExceptionMessage('Invalid role');

        $userEntity = UserEntity::forIdentify(
            uuid: Uuid::uuid7(),
            role: 0
        );
        $userEntity->getRoleName();
    }
}
