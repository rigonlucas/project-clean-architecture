<?php

namespace Core\Adapters\Framework\Contracts;

interface AuthContract
{
    public function login(string $email, string $password): string;

    public function logout(): void;

    public function userId(): ?int;

    public function userAccountsIds(): array;
}
