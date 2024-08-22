<?php

namespace Tests\Unit\Account;

use Core\Application\Account\Commons\Exceptions\AccountNameInvalidException;
use Core\Domain\Entities\Account\AccountEntity;
use Tests\TestCase;

class AccountEntityTest extends TestCase
{
    public function test_create_account_for_create_name_is_valid()
    {
        // Arrange and Act
        AccountEntity::forCreate(
            name: '1',
            uuid: 'uuid'
        );

        // Assert
        $this->expectNotToPerformAssertions();
    }

    public function test_create_account_for_create_name_is_invalid()
    {
        // Assert
        $this->expectException(AccountNameInvalidException::class);

        // Arrange and Act
        AccountEntity::forCreate(
            name: '',
            uuid: 'uuid'
        );
    }
}
