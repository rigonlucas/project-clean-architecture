<?php

namespace Tests\Feature\Persistence\User;

use App\Models\Account;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\User\Command\UserCommand;
use Infra\Services\Framework\FrameworkService;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_testar_create_de_um_usuario(): void
    {
        // Arrange
        $accountModel = Account::factory()->create();
        $userCommand = new UserCommand();
        $userEntity = UserEntity::forCreate(
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            account: AccountEntity::forDetail(
                id: $accountModel->id,
                name: $accountModel->name,
                uuid: $accountModel->uuid
            ),
            uuid: FrameworkService::getInstance()->uuid()->uuid7Generate(),
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
