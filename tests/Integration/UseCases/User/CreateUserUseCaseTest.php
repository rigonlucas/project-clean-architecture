<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Application\User\Create\Output\CreateUserOutput;
use Core\Generics\Exceptions\OutputErrorException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Dependencies\AppAdapter;
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
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        /** @var CreateUserOutput $output */
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

    /**
     * @group CreateUserUseCaseError1
     */
    public function test_must_not_create_a_user_when_email_already_exists(): void
    {
        $this->expectException(OutputErrorException::class);
        // Arrange
        $useCase = new CreateUserUseCase(
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $userFactory = User::factory()->create();
        $input = new CreateUserInput(
            name: 'name 2',
            email: $userFactory->email,
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
        $useCase = new CreateUserUseCase(
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $input = new CreateUserInput(
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

        $useCase = new CreateUserUseCase(
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $input = new CreateUserInput(
            name: 'name 2',
            email: $userFactory->email,
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
