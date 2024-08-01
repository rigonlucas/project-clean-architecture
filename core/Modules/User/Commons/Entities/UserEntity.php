<?php

namespace Core\Modules\User\Commons\Entities;

use AllowDynamicProperties;
use Core\Modules\User\Commons\Entities\Traits\HasUserEntityBuilder;
use Core\Modules\User\Commons\Entities\Traits\UserAcessorsTrait;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

#[AllowDynamicProperties] class UserEntity
{
    use HasUserEntityBuilder;
    use UserAcessorsTrait;

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
