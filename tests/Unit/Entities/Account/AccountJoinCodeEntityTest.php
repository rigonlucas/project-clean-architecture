<?php

namespace Tests\Unit\Entities\Account;

use Core\Application\Account\Shared\Exceptions\AccountJoinCodeInvalidException;
use Core\Domain\Entities\Shared\Account\JoinCode\AccountJoinCodeEntity;
use Core\Support\Http\ResponseStatus;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AccountJoinCodeEntityTest extends TestCase
{
    public function test_join_code_is_valid()
    {
        // Arrange
        $accountUuid = Uuid::uuid7();
        $entity = AccountJoinCodeEntity::forDetail(Uuid::uuid7(), '123456', $accountUuid, now()->addDay());

        // Act
        $this->expectNotToPerformAssertions();

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_blank_code()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        $accountUuid = Uuid::uuid7();

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(Uuid::uuid7(), '', $accountUuid, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_code_having_less_than_max_chars()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        $accountUuid = Uuid::uuid7();

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(Uuid::uuid7(), '12345', $accountUuid, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }


    public function test_join_code_is_invalid_with_code_having_more_than_max_chars()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        $accountUuid = Uuid::uuid7();

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(Uuid::uuid7(), '1234567', $accountUuid, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_expired_date()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);
        $accountUuid = Uuid::uuid7();

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(Uuid::uuid7(), '123456', $accountUuid, now()->subDay());

        // Assert
        $entity->validateJoinCode();
    }
}
