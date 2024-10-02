<?php

namespace Tests\Integration\UseCases\Account;

use App\Models\Account;
use App\Models\AccountJoinCode;
use App\Models\User;
use Core\Application\Account\Create\CreateAccountUseCase;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Application\Account\Shared\Exceptions\AccountJoinCodeInvalidException;
use Core\Application\Account\Shared\Exceptions\AccountNotFoundException;
use Core\Application\Account\Shared\Gateways\AccountCommandInterface;
use Core\Application\Account\Shared\Gateways\AccountMapperInterface;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Http\ResponseStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

#[Group('use_case_create_account')]
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
        $input->setUserUuid(Uuid::fromString($userFactory->uuid));
        $input->setUserName($userFactory->name);
        $input->setId($userFactory->id);

        // Act
        $accountEntity = $this->useCase->execute(input: $input);

        // Assert
        $this->assertDatabaseHas('accounts', [
            'uuid' => $accountEntity->getUuid(),
            'name' => $accountEntity->getName(),
        ]);
    }

    //create a new account success

    public function test_must_create_associate_an_user_to_an_account_with_code_access()
    {
        $userFactory = User::factory()->create();
        $accountFactory = Account::factory()->create();
        $accountJoinCode = AccountJoinCode::factory()->create([
            'account_uuid' => $accountFactory->uuid,
            'code' => '123456',
            'user_id' => null
        ]);
        // Arrange
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserUuid(Uuid::fromString($userFactory->uuid));
        $input->setUserName($userFactory->name);
        $input->setId($userFactory->id);

        // Act
        $accountEntity = $this->useCase->execute(input: $input);

        // Assert
        $this->assertDatabaseHas('accounts', [
            'uuid' => $accountEntity->getUuid()->toString(),
            'name' => $accountEntity->getName(),
        ]);
        $this->assertDatabaseHas('account_join_codes', [
            'code' => $accountJoinCode->code,
            'account_uuid' => $accountFactory->uuid,
            'user_id' => $userFactory->id
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $userFactory->id,
            'uuid' => $userFactory->uuid,
            'account_uuid' => $accountFactory->uuid
        ]);

        $this->assertEquals($accountFactory->uuid, $accountEntity->getUuid());
    }

    public function test_must_create_associate_an_user_to_an_account_with_code_access_not_existing()
    {
        $this->expectException(AccountNotFoundException::class);
        $this->expectExceptionCode(ResponseStatus::NOT_FOUND->value);
        $userFactory = User::factory()->create();
        // Arrange
        $input = new AccountInput(
            accessCode: 'ABCDEF'
        );
        $input->setUserUuid(Uuid::fromString($userFactory->uuid));

        // Act
        $this->useCase->execute(input: $input);
    }

    public function test_must_create_associate_an_user_to_an_account_with_code_access_expired()
    {
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        $userFactory = User::factory()->create();
        $accountFactory = Account::factory()->create();
        $accountJoinCode = AccountJoinCode::factory()->create([
            'account_uuid' => $accountFactory->uuid,
            'code' => '123456',
            'user_id' => null,
            'expired_at' => now()->subDay()
        ]);

        // Arrange
        $input = new AccountInput(
            accessCode: $accountJoinCode->code
        );
        $input->setUserUuid(Uuid::fromString($userFactory->uuid));

        // Act
        $this->useCase->execute(input: $input);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new CreateAccountUseCase(
            framework: $this->app->make(FrameworkContract::class)::getInstance(),
            accountCommand: $this->app->make(AccountCommandInterface::class),
            accountMapper: $this->app->make(AccountMapperInterface::class)
        );
    }
}
