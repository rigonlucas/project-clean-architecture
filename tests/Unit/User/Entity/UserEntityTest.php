<?php

namespace Tests\Unit\User\Entity;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Infra\Services\Framework\FrameworkService;
use Tests\TestCase;

/**
 * @group UserEntity
 */
class UserEntityTest extends TestCase
{
    public function test_should_return_that_the_user_is_not_of_legal_age(): void
    {
        // Arrange
        $userEntity = UserEntity::forCreate(
            name: 'John Doe',
            email: '',
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
            email: 'john@email.com',
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
}
