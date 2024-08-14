<?php

namespace Tests\Integration\UseCases\User;

use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\AccountInput;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\Account\Command\AccountCommand;
use Infra\Database\Account\Repository\AccountRepository;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;
use Ramsey\Uuid\Uuid;
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
            userRepository: new UserRepository(),
            accountCommandInterface: new AccountCommand(),
            accountRepository: new AccountRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );
        $accountInput = new AccountInput(
            'AA',
            Uuid::uuid7()->toString()
        );

        // Act
        $UserEntity = $useCase->execute($input, $accountInput);

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
