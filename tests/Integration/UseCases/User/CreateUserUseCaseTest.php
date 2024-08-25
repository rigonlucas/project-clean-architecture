<?php

namespace Tests\Integration\UseCases\User;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\HasErrorsInBagException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @group UseCaseCreateUser
 */
class CreateUserUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    protected CreateUserUseCase $useCase;

    public function test_must_create_a_user(): void
    {
        // Arrange
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $UserEntity = $this->useCase->execute($input);

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
        $this->expectException(HasErrorsInBagException::class);
        $this->expectExceptionCode(ResponseStatus::UNPROCESSABLE_ENTITY->value);

        // Arrange
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(17)
        );

        // Act
        $this->useCase->execute($input);
    }

    public function test_must_not_create_a_user_with_invalid_email(): void
    {
        $this->expectException(HasErrorsInBagException::class);
        $this->expectExceptionCode(ResponseStatus::UNPROCESSABLE_ENTITY->value);

        // Arrange
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $this->useCase->execute($input);
    }

    public function test_must_not_create_a_user_with_invalid_age(): void
    {
        $this->expectException(HasErrorsInBagException::class);
        $this->expectExceptionCode(ResponseStatus::UNPROCESSABLE_ENTITY->value);

        // Arrange
        $input = new CreateUserInput(
            name: 'name 2',
            email: 'email3@gmail.com',
            password: 'password',
            birthday: now()->subYears(17)
        );

        // Act
        $this->useCase->execute($input);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new CreateUserUseCase(
            framework: $this->app->make(FrameworkContract::class)::getInstance(),
            userCommand: $this->app->make(UserCommandInterface::class),
            userMapper: $this->app->make(UserMapperInterface::class)
        );
    }
}
