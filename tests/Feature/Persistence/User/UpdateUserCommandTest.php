<?php

namespace Tests\Feature\Persistence\User;

use App\Models\User;
use Core\Application\User\Commons\Entities\User\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Tests\TestCase;

class UpdateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_atualizar_um_usuario(): void
    {
        // Arrange
        $userModel = User::factory()->create();
        $userCommand = new UserCommand();

        $userEntity = UserEntity::forUpdate(
            id: $userModel->id,
            name: 'name 2',
            email: 'email@3',
            password: 'password',
            birthday: now()->subYears(18)
        );
        $userEntity->setId($userModel->id);

        // Act
        $userCommand->update($userEntity);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday()
        ]);

        $this->assertNotEquals($userModel->name, $userEntity->getName());
        $this->assertNotEquals($userModel->email, $userEntity->getEmail());
        $this->assertNotEquals($userModel->birthday, $userEntity->getBirthday());
    }
}
