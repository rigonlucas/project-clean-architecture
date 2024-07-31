<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Create\CreateUserUseCase;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutput;
use Core\Modules\User\Create\Output\CreateUserOutputError;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;
use Tests\TestCase;

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
        $useCase->execute($input);
        /** @var CreateUserOutput $output */
        $output = $useCase->getOutput();

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $output->userEntity->getId(),
            'name' => $output->userEntity->getName(),
            'email' => $output->userEntity->getEmail(),
            'password' => $output->userEntity->getPassword(),
            'birthday' => $output->userEntity->getBirthday()
        ]);
        $this->assertEquals(ResponseStatusCodeEnum::CREATED->value, $output->status->statusCode);
        $this->assertNotEquals($input->password, $output->userEntity->getPassword());
    }

    public function test_must_not_create_a_user_when_email_already_exists(): void
    {
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
        $useCase->execute($input);

        // Assert
        $this->assertInstanceOf(CreateUserOutputError::class, $useCase->getOutput());
    }

    public function test_must_not_create_a_user_when_age_is_invalid(): void
    {
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

        // Act
        $useCase->execute($input);

        // Assert
        $this->assertTrue($useCase->hasErrorBag());
        $this->assertInstanceOf(CreateUserOutputError::class, $useCase->getOutput());
    }

    public function test_must_not_create_a_user_when_age_and_email_already_exists_is_invalid(): void
    {
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

        // Act
        $useCase->execute($input);

        // Assert exception
        $this->assertTrue($useCase->hasErrorBag());
        $this->assertInstanceOf(CreateUserOutputError::class, $useCase->getOutput());
    }
}
