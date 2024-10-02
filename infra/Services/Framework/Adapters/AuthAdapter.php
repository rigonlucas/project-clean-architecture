<?php

namespace Infra\Services\Framework\Adapters;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\Contracts\AuthContract;
use Core\Support\HasSingletonTrait;
use Exception;
use Ramsey\Uuid\Uuid;

class AuthAdapter implements AuthContract
{
    use HasSingletonTrait;

    public function loginFromApi(string $email, string $password): void
    {
        throw new Exception('Method not implemented');
    }

    public function logoutFromApi(): void
    {
        throw new Exception('Method not implemented');
    }

    public function userId(): ?int
    {
        return auth()?->id();
    }

    public function user(): UserEntity
    {
        return UserEntity::forIdentify(
            id: auth()->id(),
            uuid: Uuid::fromString(auth()->user()->uuid),
            role: auth()->user()->role,
            accountUuid: Uuid::fromString(auth()->user()->account_uuid),
        );
    }
}