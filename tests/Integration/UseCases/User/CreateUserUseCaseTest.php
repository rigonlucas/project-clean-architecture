<?php

namespace Tests\Integration\UseCases\User;

use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;
use Tests\TestCase;

/**
 * @group UseCaseCreateUser
 */
class CreateUserUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_must_create_a_user(): void
    {
        // Arrange
        $useCase = new CreateUserUseCase(
            framework: FrameworkService::getInstance(),
            createUserInterface: new UserCommand(),
            userRepository: new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $UserEntity = $useCase->execute($input);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $UserEntity->getId(),
            'name' => $UserEntity->getName(),
            'email' => $UserEntity->getEmail(),
            'password' => $UserEntity->getPassword(),
            'birthday' => $UserEntity->getBirthday()
        ]);
        $this->assertNotEquals($input->password, $UserEntity->getPassword());
    }
}
