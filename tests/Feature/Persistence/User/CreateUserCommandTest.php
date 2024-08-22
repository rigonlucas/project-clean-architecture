<?php

namespace Tests\Feature\Persistence\User;

use App\Models\Account;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
            email: 'email3@email.com',
            password: 'password',
            account: AccountEntity::forDetail(
                id: $accountModel->id,
                name: $accountModel->name,
                uuid: $accountModel->uuid
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
