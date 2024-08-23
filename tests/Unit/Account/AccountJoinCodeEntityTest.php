<?php

namespace Tests\Unit\Account;

use Core\Application\Account\Commons\Exceptions\AccountJoinCodeInvalidException;
use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use Core\Support\Http\ResponseStatusCodeEnum;
use Tests\TestCase;

class AccountJoinCodeEntityTest extends TestCase
{
    public function test_join_code_is_valid()
    {
        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(1, '123456', 1, now()->addDay());

        // Act
        $this->expectNotToPerformAssertions();

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_blank_code()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::BAD_REQUEST->value);

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(1, '', 1, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_code_having_less_than_max_chars()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::BAD_REQUEST->value);

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(1, '12345', 1, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }


    public function test_join_code_is_invalid_with_code_having_more_than_max_chars()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::BAD_REQUEST->value);

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(1, '1234567', 1, now()->addDay());

        // Assert
        $entity->validateJoinCode();
    }

    public function test_join_code_is_invalid_with_expired_date()
    {
        // Assert
        $this->expectException(AccountJoinCodeInvalidException::class);
        $this->expectExceptionCode(ResponseStatusCodeEnum::BAD_REQUEST->value);

        // Arrange
        $entity = AccountJoinCodeEntity::forDetail(1, '123456', 1, now()->subDay());

        // Assert
        $entity->validateJoinCode();
    }
}
