<?php

namespace Core\Adapters\App;

interface AppInterface
{
    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;
}