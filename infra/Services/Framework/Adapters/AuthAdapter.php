<?php

namespace Infra\Services\Framework\Adapters;

use Core\Services\Framework\Contracts\AuthContract;
use Core\Support\HasSingletonTrait;

class AuthAdapter implements AuthContract
{
    use HasSingletonTrait;

    public function login(string $email, string $password): void
    {
        // TODO: Implement login() method.
    }

    public function logout(): void
    {
        // TODO: Implement logout() method.
    }

    public function userId(): ?int
    {
        return auth()?->id();
    }

    public function userAccountsIds(): array
    {
        return auth()->user()->accounts->toArray();
    }
}