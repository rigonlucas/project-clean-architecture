<?php

namespace Tests\Integration\UseCases\User;

use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
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

    public function test_must_not_create_a_user_with_invalid_email_and_birthday(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $useCase = new CreateUserUseCase(
            framework: FrameworkService::getInstance(),
            createUserInterface: new UserCommand(),
            userRepository: new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(17)
        );

        // Act
        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('birthday', $useCase->getErrorBag());
            $this->assertArrayHasKey('email', $useCase->getErrorBag());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_with_invalid_email(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $useCase = new CreateUserUseCase(
            framework: FrameworkService::getInstance(),
            createUserInterface: new UserCommand(),
            userRepository: new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('email', $useCase->getErrorBag());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_with_invalid_age(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $useCase = new CreateUserUseCase(
            framework: FrameworkService::getInstance(),
            createUserInterface: new UserCommand(),
            userRepository: new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@gmail.com',
            password: 'password',
            birthday: now()->subYears(17)
        );

        // Act
        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('birthday', $useCase->getErrorBag());
            throw $e;
        }
    }
}
