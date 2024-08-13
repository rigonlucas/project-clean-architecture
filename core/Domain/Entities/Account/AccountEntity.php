<?php

namespace Core\Domain\Entities\Account;

use Core\Domain\Entities\Account\Traits\AccountEntityAcessors;
use Core\Domain\Entities\Account\Traits\AccountEntityBuilder;

class AccountEntity
{
    use AccountEntityAcessors;
    use AccountEntityBuilder;

    private ?int $id = null;
    private ?string $name = null;
    private ?string $uuid = null;

    private function __construct()
    {
    }

    public function isNameValid(): bool
    {
        return !empty($this->name);
    }
}
