<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Dependencies\Framework\Framework;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UpdateUserUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_must_create_a_user(): void
    {
        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $useCase = new UpdateUserUseCase(
            Framework::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $userEntity = $useCase->execute($input);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $userEntity->getId(),
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'password' => $userEntity->getPassword(),
            'birthday' => $userEntity->getBirthday()
        ]);
        $this->assertNotEquals($input->password, $userEntity->getPassword());
    }
}
