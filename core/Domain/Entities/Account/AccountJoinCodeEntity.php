<?php

namespace Core\Domain\Entities\Account;

use Core\Application\Account\Commons\Exceptions\AccountJoinCodeInvalidException;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeAccessors;
use Core\Domain\Entities\Account\Traits\JoinCode\AccountJoinCodeBuilder;
use Core\Support\Http\ResponseStatus;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class AccountJoinCodeEntity
{
    use AccountJoinCodeAccessors;
    use AccountJoinCodeBuilder;

    private ?UuidInterface $uuid = null;
    private ?string $code = null;
    private ?UuidInterface $accountId = null;
    private ?DateTimeInterface $expiresAt = null;

    private function __construct()
    {
    }

    public function validateJoinCode(): void
    {
        if ($this->expiresAt && $this->expiresAt < new DateTime()) {
            throw new AccountJoinCodeInvalidException(
                'Join code has expired',
                ResponseStatus::BAD_REQUEST->value
            );
        }

        if (strlen($this->code) !== 6) {
            throw new AccountJoinCodeInvalidException(
                'Join code must be 6 characters long',
                ResponseStatus::BAD_REQUEST->value
            );
        }
    }

}
