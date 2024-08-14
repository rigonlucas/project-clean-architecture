<?php

namespace Infra\Services\Framework\Adapters;

use Core\Adapters\Framework\Contracts\AuthContract;

class AuthAdapter implements AuthContract
{
    private static ?AuthAdapter $instance = null;

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