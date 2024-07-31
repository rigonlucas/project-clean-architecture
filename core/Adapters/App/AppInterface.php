<?php

namespace Core\Adapters\App;

use Ramsey\Uuid\UuidInterface;

interface AppInterface
{
    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;

    public function uuid5Generate(string $name): UuidInterface;

    public function uuidFromString(string $uuid): UuidInterface;
}