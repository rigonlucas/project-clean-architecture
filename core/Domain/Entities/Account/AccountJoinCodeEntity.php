<?php

namespace Core\Domain\Entities\Account;

use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeAccessors;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeBuilder;

class AccountJoinCodeEntity
{
    use AccountJoinCodeAccessors;
    use AccountJoinCodeBuilder;

    private ?int $id = null;
    private ?string $code = null;
    private ?int $account_id = null;
    private ?int $user_id = null;
    
    public function isCodeValid(): bool
    {
        return strlen($this->code) === 6;
    }

}
