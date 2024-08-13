<?php

namespace Core\Domain\Entities\Account;

use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeAccessors;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeBuilder;
use DateTime;
use DateTimeInterface;

class AccountJoinCodeEntity
{
    use AccountJoinCodeAccessors;
    use AccountJoinCodeBuilder;

    private ?int $id = null;
    private ?string $code = null;
    private ?int $accountId = null;
    private ?int $userId = null;
    private ?DateTimeInterface $expiresAt = null;

    public function isCodeValid(): bool
    {
        return strlen($this->code) === 6 && $this->expiresAt > new DateTime();
    }

}
