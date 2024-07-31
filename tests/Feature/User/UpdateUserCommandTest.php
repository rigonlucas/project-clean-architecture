<?php

namespace Tests\Feature\User;

use App\Models\User;
use Core\Modules\User\Commons\Entities\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Persistence\User\Command\UserCommand;
use Tests\TestCase;

class UpdateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_atualizar_um_usuario(): void
    {
        // Arrange
        $userModel = User::factory()->create();
        $userCommand = new UserCommand();

        $userEntity = UserEntity::update(
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
