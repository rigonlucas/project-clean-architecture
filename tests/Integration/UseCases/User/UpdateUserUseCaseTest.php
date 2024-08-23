<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group UseCaseUpdateUser
 */
class UpdateUserUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    protected UpdateUserUseCase $useCase;
    protected User $user;

    public function test_must_update_a_user(): void
    {
        // Arrange
        $this->user->update([
            'birthday' => now()->subYears(19)
        ]);
        $this->user->save();

        $input = new UpdateUserInput(
            uuid: Uuid::fromString($this->user->uuid),
            name: 'name 2',
            email: new EmailValueObject('email3@email.com'),
            password: 'password',
            birthday: now()->subYears(18),
            authenticableUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid)
            )
        );

        // Act
        $userEntity = $this->useCase->execute($input);

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

    public function test_must_not_update_a_user_with_invalid_email_and_birthday(): void
    {
        $this->expectException(OutputErrorException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);

        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: new EmailValueObject('email3'),
            password: 'password',
            birthday: now()->subYears(17),
            authenticableUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid)
            )
        );

        $this->useCase->execute($input);
    }

    public function test_user_not_found_must_throw_an_exception(): void
    {
        $this->expectException(UserNotFountException::class);
        $this->expectExceptionCode(ResponseStatus::NOT_FOUND->value);

        // Arrange
        $input = new UpdateUserInput(
            uuid: Uuid::uuid7(),
            name: 'name 2',
            email: new EmailValueObject('email3@gmail.com'),
            password: 'password',
            birthday: now()->subYears(18),
            authenticableUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid)
            )
        );

        $this->useCase->execute($input);
    }

    public function test_must_not_update_a_user_with_invalid_birthday(): void
    {
        $this->expectException(OutputErrorException::class);
        $this->expectExceptionCode(ResponseStatus::UNPROCESSABLE_ENTITY->value);

        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $input = new UpdateUserInput(
            uuid: Uuid::fromString($userFactory->uuid),
            name: 'name 2',
            email: new EmailValueObject('email3@gmail.com'),
            password: 'password',
            birthday: now()->subYears(17),
            authenticableUser: UserEntity::forIdentify(
                id: $this->user->id,
                uuid: Uuid::fromString($this->user->uuid)
            )
        );

        $useCase = new UpdateUserUseCase(
            $this->app->make(FrameworkContract::class)::getInstance(),
            $this->app->make(UserRepositoryInterface::class),
            $this->app->make(UserCommandInterface::class)
        );

        $useCase->execute($input);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new UpdateUserUseCase(
            $this->app->make(FrameworkContract::class)::getInstance(),
            $this->app->make(UserRepositoryInterface::class),
            $this->app->make(UserCommandInterface::class)
        );
        $this->user = User::factory()->create();
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
    }
}
