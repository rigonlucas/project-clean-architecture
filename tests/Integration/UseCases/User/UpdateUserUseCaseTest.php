<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group UseCaseUpdateUser
 */
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
            FrameworkService::getInstance(),
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

    public function test_must_not_create_a_user_with_invalid_email_and_birthday(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(17)
        );

        try {
            // Act
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'id' => $userFactory->id,
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('email', $useCase->getErrorBag());
            $this->assertArrayHasKey('birthday', $useCase->getErrorBag());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_with_invalid_email(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(18)
        );

        try {
            // Act
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'id' => $userFactory->id,
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('email', $useCase->getErrorBag());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_with_invalid_birthday(): void
    {
        $this->expectException(OutputErrorException::class);

        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: 'email3@gmail.com',
            password: 'password',
            birthday: now()->subYears(17)
        );

        try {
            // Act
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            // Assert
            $this->assertDatabaseMissing('users', [
                'id' => $userFactory->id,
                'name' => $input->name,
                'email' => $input->email,
                'password' => $input->password,
                'birthday' => $input->birthday
            ]);
            $this->assertArrayHasKey('birthday', $useCase->getErrorBag());
            throw $e;
        }
    }

    public function test_user_not_found_must_throw_an_exception(): void
    {
        $this->expectException(UserNotFountException::class);

        // Arrange
        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::uuid7(),
            name: 'name 2',
            email: 'email3@gmail.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        $useCase->execute($input);
    }
}
