<?php

namespace Tests\Integration\UseCases\Account;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Commons\Exceptions\AccountJoinCodeInvalidException;
use Core\Application\Account\Commons\Exceptions\AccountNotFoundException;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\Account\Create\CreateAccountUseCase;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Http\ResponseStatusCodeEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @group UseCaseCreateAccount
 */
class CreateAccountUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    protected CreateAccountUseCase $useCase;

    public function test_must_create_a_new_account_for_an_user_when_access_code_is_null()
    {
        $userFactory = User::factory()->create();
        // Arrange
        $input = new AccountInput(
            accessCode: null
        );
        $input->setUserId($userFactory->id);
        $input->setUserNane($userFactory->name);

        // Act
        $accountEntity = $this->useCase->execute(input: $input);

        // Assert
        $this->assertDatabaseHas('accounts', [
            'id' => $accountEntity->getId(),
            'name' => $accountEntity->getName(),
        ]);
    }

    //create a new account success

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
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserId($userFactory->id);
        $input->setUserNane($userFactory->name);

        // Act
        $accountEntity = $this->useCase->execute(input: $input);

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
        $this->expectExceptionCode(ResponseStatusCodeEnum::NOT_FOUND->value);
        $userFactory = User::factory()->create();
        // Arrange
        $input = new AccountInput(
            accessCode: 'ABCDEF'
        );
        $input->setUserId($userFactory->id);

        // Act
        $this->useCase->execute(input: $input);
    }

    public function test_must_create_associate_an_user_to_an_account_with_code_access_expired()
    {
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::BAD_REQUEST->value);
        $userFactory = User::factory()->create();
        $accountFactory = Account::factory()->create();
        $accountJoinCode = AccountJoinCode::factory()->create([
            'account_id' => $accountFactory->id,
            'code' => '123456',
            'user_id' => null,
            'expired_at' => now()->subDay()
        ]);

        // Arrange
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserId($userFactory->id);

        // Act
        $this->useCase->execute(input: $input);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new CreateAccountUseCase(
            framework: $this->app->make(FrameworkContract::class)::getInstance(),
            accountCommand: $this->app->make(AccountCommandInterface::class),
            accountRepository: $this->app->make(AccountRepositoryInterface::class)
        );
    }
}
