<?php

namespace Core\Adapters\Framework;

interface AppContract
{
    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;
}