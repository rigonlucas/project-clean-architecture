<?php

namespace Core\Application\User\Commons\Entities\User;

use Core\Application\User\Commons\Entities\User\Traits\HasUserEntityBuilder;
use Core\Application\User\Commons\Entities\User\Traits\UserEntityAcessors;
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

    private function __construct()
    {
    }

    public function hasNoLegalAge(): bool
    {
        return !($this->birthday->diff(new DateTime())->y >= 18);
    }
}
