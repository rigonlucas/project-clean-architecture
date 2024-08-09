<?php

namespace Tests\Feature\Persistence\User;

use Core\Application\User\Commons\Entities\User\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Dependencies\AppAdapter;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_create_de_um_usuario(): void
    {
        // Arrange
        $userCommand = new UserCommand();
        $userEntity = UserEntity::forCreate(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            uuid: AppAdapter::getInstance()->uuid7Generate(),
            birthday: now()->subYears(18)
        );
        // Act
        $userCommand->create($userEntity);

        // Assert
        $this->assertDatabaseHas('users', [
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday()
        ]);
    }
}
