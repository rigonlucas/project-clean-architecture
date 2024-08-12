<?php

namespace Infra\Dependencies\Framework\Concerns;

use Core\Adapters\Framework\Contracts\AuthContract;

class AuthAdapter implements AuthContract
{
    private static AuthAdapter $instance;

    private function __construct()
    {
    }

    public static function getInstance(): AuthAdapter
    {
        if (self::$instance === null) {
            self::$instance = new AuthAdapter();
        }

        return self::$instance;
    }

    public function login(string $email, string $password): string
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