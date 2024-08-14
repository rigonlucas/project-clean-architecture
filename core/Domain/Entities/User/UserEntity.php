<?php

namespace Core\Domain\Entities\User;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\Traits\HasUserEntityBuilder;
use Core\Domain\Entities\User\Traits\UserEntityAcessors;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class UserEntity
{
    use HasUserEntityBuilder;
    use UserEntityAcessors;

    private ?int $id = null;
    private string $name;
    private ?string $email;
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
