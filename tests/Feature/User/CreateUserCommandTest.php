<?php

namespace Tests\Feature\User;

use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Commons\Entities\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Persistence\User\Command\UserCommand;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_create_de_um_usuario(): void
    {
        // Arrange
        $userCommand = new UserCommand();
        $userEntity = UserEntity::create(
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
