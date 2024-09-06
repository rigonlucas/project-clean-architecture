<?php

namespace Tests\Unit\Entities\Account;

use Core\Application\Account\Shared\Exceptions\AccountNameInvalidException;
use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Support\Http\ResponseStatus;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AccountEntityTest extends TestCase
{
    public function test_create_account_for_create_name_is_valid()
    {
        // Arrange and Act
        AccountEntity::forCreate(
            name: '1',
            uuid: Uuid::uuid7()
        );

        // Assert
        $this->expectNotToPerformAssertions();
    }

    public function test_create_account_for_create_name_is_invalid()
    {
        // Assert
        $this->expectException(AccountNameInvalidException::class);
        $this->expectExceptionCode(ResponseStatus::BAD_REQUEST->value);

        // Arrange and Act
        AccountEntity::forCreate(
            name: '',
            uuid: Uuid::uuid7()
        );
    }
}
