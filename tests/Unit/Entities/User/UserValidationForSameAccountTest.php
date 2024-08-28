<?php

namespace Tests\Unit\Entities\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Permissions\UserRoles;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group user_entity_validation_for_same_account
 */
class UserValidationForSameAccountTest extends TestCase
{
    public function test_success_for_users_from_different_accounts()
    {
        $this->expectException(ForbidenException::class);
        $user1 = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountId: 1
        );

        $user2 = UserEntity::forIdentify(
            id: 2,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountId: 2
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }

    public function test_success_for_users_from_same_account()
    {
        $this->expectNotToPerformAssertions();
        $user1 = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountId: 1
        );

        $user2 = UserEntity::forIdentify(
            id: 2,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountId: 1
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }
}
