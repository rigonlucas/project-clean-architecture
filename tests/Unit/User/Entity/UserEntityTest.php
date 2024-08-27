<?php

namespace Tests\Unit\User\Entity;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Permissions\UserRoles;
use Infra\Services\Framework\FrameworkService;
use Tests\TestCase;

/**
 * @group user_entity
 */
class UserEntityTest extends TestCase
{
    public function test_should_return_that_the_user_is_not_of_legal_age(): void
    {
        // Arrange
        $userEntity = UserEntity::forCreate(
            name: 'John Doe',
            email: new EmailValueObject('email@email.com', false),
            password: 'password',
            account: AccountEntity::forDetail(
                id: 1,
                name: 'Account',
                uuid: FrameworkService::getInstance()->uuid()->uuid7Generate()
            ),
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            birthday: now()->subYears(17)
        );
        $this->assertTrue($userEntity->underAge());
    }

    public function test_should_return_user_name(): void
    {
        // Arrange
        $userEntity = UserEntity::forCreate(
            name: 'John Doe',
            email: new EmailValueObject('john@email.com', false),
            password: 'password',
            account: AccountEntity::forDetail(
                id: 1,
                name: 'Account',
                uuid: FrameworkService::getInstance()->uuid()->uuid7Generate()
            ),
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            birthday: now()->subYears(18)
        );
        // Act
        $this->assertFalse($userEntity->underAge());
        $name = $userEntity->getName();

        // Assert
        $this->assertEquals('John Doe', $name);
    }

    public function test_user_admin_can_access_email_not_suppresed()
    {
        $userEntity = UserEntity::forDetail(
            id: 1,
            name: 'Aaaa',
            email: 'email@email.com',
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            account: AccountEntity::forIdentify(1),
            birthday: now(),
            role: UserRoles::ADMIN
        );

        $requireUserEntity = UserEntity::forIdentify(
            id: 1,
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            role: UserRoles::ADMIN
        );

        $this->assertTrue($userEntity->getEmailWithAccessControl($requireUserEntity)->isNotSuppressed());
    }


    public function test_user_editor_can_access_email_suppresed()
    {
        $userEntity = UserEntity::forDetail(
            id: 1,
            name: 'Aaaa',
            email: 'email@email.com',
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            account: AccountEntity::forIdentify(1),
            birthday: now(),
            role: UserRoles::EDITOR
        );
        $this->assertTrue($userEntity->getEmail()->isNotSuppressed());
    }

    public function test_userviewer_can_access_email_suppresed()
    {
        $userEntity = UserEntity::forDetail(
            id: 1,
            name: 'Aaaa',
            email: 'email@email.com',
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            account: AccountEntity::forIdentify(1),
            birthday: now(),
            role: UserRoles::VIEWER
        );

        $requireUserEntity = UserEntity::forIdentify(
            id: 1,
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            role: UserRoles::VIEWER
        );

        $this->assertTrue($userEntity->getEmailWithAccessControl($requireUserEntity)->isSuppressed());
    }

    public function test_must_throw_exception_forbiden_when_no_email_defined()
    {
        $userEntity = UserEntity::forIdentify(
            id: 1,
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
            role: UserRoles::VIEWER
        );
        $this->assertEquals(
            EmailValueObject::DEFAULT_MASK_SUPPRESSED_EMAIL,
            $userEntity->getEmail()->supress()
        );
    }
}
