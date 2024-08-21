<?php

namespace Tests\Integration\UseCases\Account;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Commons\Exceptions\AccountNotFoundException;
use Core\Application\Account\Create\CreateAccountUseCase;
use Core\Application\Account\Create\Inputs\AccountInput;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Database\Account\Command\AccountCommand;
use Infra\Database\Account\Repository\AccountRepository;
use Infra\Services\Framework\FrameworkService;
use Tests\TestCase;

/**
 * @group UseCaseCreateAccount
 */
class CreateAccountUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    //create a new account success
    public function test_must_create_a_new_account_for_an_user_when_access_code_is_null()
    {
        $userFactory = User::factory()->create();
        // Arrange
        $useCase = new CreateAccountUseCase(
            framework: FrameworkService::getInstance(),
            accountCommand: new AccountCommand(),
            accountRepository: new AccountRepository()
        );
        $input = new AccountInput(
            accessCode: null
        );
        $input->setUserId($userFactory->id);
        $input->setUserNane($userFactory->name);

        // Act
        $accountEntity = $useCase->execute(input: $input);

        // Assert
        $this->assertDatabaseHas('accounts', [
            'id' => $accountEntity->getId(),
            'name' => $accountEntity->getName(),
        ]);
    }


    public function test_must_create_associate_an_user_to_an_account_with_code_access()
    {
        $userFactory = User::factory()->create();
        $accountFactory = Account::factory()->create();
        $accountJoinCode = AccountJoinCode::factory()->create([
            'account_id' => $accountFactory->id,
            'code' => '123456',
            'user_id' => null
        ]);
        // Arrange
        $useCase = new CreateAccountUseCase(
            framework: FrameworkService::getInstance(),
            accountCommand: new AccountCommand(),
            accountRepository: new AccountRepository()
        );
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserId($userFactory->id);
        $input->setUserNane($userFactory->name);

        // Act
        $accountEntity = $useCase->execute(input: $input);

        // Assert
        $this->assertDatabaseHas('accounts', [
            'id' => $accountEntity->getId(),
            'name' => $accountEntity->getName(),
        ]);
        $this->assertDatabaseHas('account_join_codes', [
            'code' => $accountJoinCode->code,
            'account_id' => $accountFactory->id,
            'user_id' => $userFactory->id
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $userFactory->id,
            'account_id' => $accountFactory->id
        ]);

        $this->assertEquals($accountFactory->id, $accountEntity->getId());
    }

    public function test_must_create_associate_an_user_to_an_account_with_code_access_not_existing()
    {
        $this->expectException(AccountNotFoundException::class);
        $userFactory = User::factory()->create();
        // Arrange
        $useCase = new CreateAccountUseCase(
            framework: FrameworkService::getInstance(),
            accountCommand: new AccountCommand(),
            accountRepository: new AccountRepository()
        );
        $input = new AccountInput(
            accessCode: 'ABCDEF'
        );
        $input->setUserId($userFactory->id);

        // Act
        $useCase->execute(input: $input);
    }

    public function test_must_create_associate_an_user_to_an_account_with_code_access_expired()
    {
        $this->expectException(AccountNotFoundException::class);
        $userFactory = User::factory()->create();
        $accountFactory = Account::factory()->create();
        $accountJoinCode = AccountJoinCode::factory()->create([
            'account_id' => $accountFactory->id,
            'code' => '123456',
            'user_id' => null,
            'expired_at' => now()->subDay()
        ]);

        // Arrange
        $useCase = new CreateAccountUseCase(
            framework: FrameworkService::getInstance(),
            accountCommand: new AccountCommand(),
            accountRepository: new AccountRepository()
        );
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserId($userFactory->id);

        // Act
        $useCase->execute(input: $input);
    }
}
