<?php

namespace Tests\Feature\Persistence\User;

use App\Models\Account;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Services\Framework\FrameworkContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group("test_create_user_command")]
class CreateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_create_de_um_usuario(): void
    {
        // Arrange
        $accountModel = Account::factory()->create();
        $userCommand = $this->app->make(UserCommandInterface::class);
        $userEntity = UserEntity::forCreate(
            name: 'name 2',
            email: new EmailValueObject('email3@email.com', false),
            password: 'password',
            account: AccountEntity::forDetail(
                uuid: Uuid::fromString($accountModel->uuid),
                name: $accountModel->name
            ),
            uuid: $this->app->make(FrameworkContract::class)::getInstance()->uuid()->uuid7Generate(),
            birthday: now()->subYears(18)
        );
        // Act
        $userCommand->create($userEntity);

        // Assert
        $this->assertDatabaseHas('users', [
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday()
        ]);
    }
}
