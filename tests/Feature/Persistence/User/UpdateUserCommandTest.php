<?php

namespace Tests\Feature\Persistence\User;

use App\Models\User;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UpdateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_atualizar_um_usuario(): void
    {
        // Arrange
        $userModel = User::factory()->create();
        $userCommand = $this->app->make(UserCommandInterface::class);

        $userEntity = UserEntity::forUpdate(
            uuid: Uuid::fromString($userModel->uuid),
            name: 'name 2',
            email: 'email@3',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $userCommand->update($userEntity);

        // Assert
        $this->assertDatabaseHas('users', [
            'uuid' => $userEntity->getUuid()->toString(),
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday()
        ]);

        $this->assertEquals($userModel->uuid, $userEntity->getUuid()->toString());
        $this->assertNotEquals($userModel->name, $userEntity->getName());
        $this->assertNotEquals($userModel->email, $userEntity->getEmail());
        $this->assertNotEquals($userModel->birthday, $userEntity->getBirthday());
    }
}
