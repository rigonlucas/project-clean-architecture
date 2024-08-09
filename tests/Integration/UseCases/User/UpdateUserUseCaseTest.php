<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Dependencies\AppAdapter;
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
            AppAdapter::getInstance(),
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
        $output = $useCase->execute($input);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $output->userEntity->getId(),
            'name' => $output->userEntity->getName(),
            'email' => $output->userEntity->getEmail(),
            'password' => $output->userEntity->getPassword(),
            'birthday' => $output->userEntity->getBirthday()
        ]);
        $this->assertNotEquals($input->password, $output->userEntity->getPassword());
    }

    public function test_must_not_create_a_user_when_email_already_exists(): void
    {
        $this->expectException(OutputErrorException::class);
        // Arrange
        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $userFactory = User::factory()->create();
        $otherUserFactory = User::factory()->create();
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: $otherUserFactory->email,
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            $this->assertArrayHasKey('email', $e->getErrors());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_when_age_is_invalid(): void
    {
        $this->expectException(OutputErrorException::class);
        // Arrange
        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $userFactory = User::factory()->create();
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: 'email@email.com',
            password: 'password',
            birthday: now()->subYears(17)
        );

        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            $this->assertArrayHasKey('birthday', $e->getErrors());
            throw $e;
        }
    }

    public function test_must_not_create_a_user_when_age_and_email_already_exists_is_invalid(): void
    {
        $this->expectException(OutputErrorException::class);
        // Arrange
        $userFactory = User::factory()->create();
        $otherUserFactory = User::factory()->create();

        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: $otherUserFactory->email,
            password: 'password',
            birthday: now()->subYears(17)
        );

        try {
            $useCase->execute($input);
        } catch (OutputErrorException $e) {
            $this->assertArrayHasKey('email', $e->getErrors());
            $this->assertArrayHasKey('birthday', $e->getErrors());
            throw $e;
        }
    }

}
