<?php

namespace Core\Services\Framework\Contracts;

use Core\Domain\Entities\User\UserEntity;

interface AuthContract
{
    public function loginFromApi(string $email, string $password): void;

    public function logoutFromApi(): void;

    public function userId(): ?int;

    public function user(): UserEntity;
}
