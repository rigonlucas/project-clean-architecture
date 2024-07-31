<?php

namespace Core\Adapters\App;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

interface AppInterface
{
    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;

    public function uuid7Generate(?DateTimeInterface $dateTime = null): UuidInterface;

    public function uuidFromString(string $uuid): UuidInterface;
}