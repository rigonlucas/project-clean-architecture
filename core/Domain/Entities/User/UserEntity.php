<?php

namespace Core\Domain\Entities\User;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\Traits\HasUserAccountValidator;
use Core\Domain\Entities\User\Traits\HasUserEntityBuilder;
use Core\Domain\Entities\User\Traits\HasUserRoleTrait;
use Core\Domain\Entities\User\Traits\UserEntityAcessors;
use Core\Domain\ValueObjects\EmailValueObject;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class UserEntity
{
    use HasUserEntityBuilder;
    use UserEntityAcessors;
    use HasUserRoleTrait;
    use HasUserAccountValidator;

    private ?int $id = null;
    private string $name;
    private ?EmailValueObject $email;
    private ?string $password;
    private UuidInterface $uuid;
    private ?DateTimeInterface $birthday;
    private ?AccountEntity $account;

    private function __construct()
    {
    }

    public function underAge(): bool
    {
        return $this->birthday->diff(new DateTime())->y < 18;
    }
}
