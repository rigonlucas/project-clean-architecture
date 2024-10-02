<?php

namespace Tests\Unit\Entities\User;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Permissions\UserRoles;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('user_entity_validation_for_same_account')]
class UserValidationForSameAccountTest extends TestCase
{
    public function test_success_for_users_from_different_accounts()
    {
        $this->expectException(ForbidenException::class);
        $user1 = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: Uuid::uuid7()
        );

        $user2 = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: Uuid::uuid7()
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }

    public function test_success_for_users_from_same_account()
    {
        $this->expectNotToPerformAssertions();
        $accountUuid = Uuid::uuid7();
        $userUuid = Uuid::uuid7();
        $user1 = UserEntity::forIdentify(
            id: 1,
            uuid: $userUuid,
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );

        $user2 = UserEntity::forIdentify(
            id: 1,
            uuid: Uuid::uuid7(),
            role: UserRoles::ADMIN,
            accountUuid: $accountUuid
        );
        $user1->checkUsersAreFromSameAccount($user2);
    }
}
