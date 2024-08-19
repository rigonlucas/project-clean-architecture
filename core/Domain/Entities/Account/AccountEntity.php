<?php

namespace Core\Domain\Entities\Account;

use Core\Domain\Entities\Account\Traits\Account\AccountEntityAcessors;
use Core\Domain\Entities\Account\Traits\Account\AccountEntityBuilder;

class AccountEntity
{
    use AccountEntityAcessors;
    use AccountEntityBuilder;

    private ?int $id = null;
    private ?string $name = null;
    private ?string $uuid = null;
    private ?AccountJoinCodeEntity $joinCodeEntity = null;

    private function __construct()
    {
    }
}
