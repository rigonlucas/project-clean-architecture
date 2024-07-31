<?php

namespace Core\Adapters\App;

use DateTimeInterface;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AppAdapter implements AppInterface
{
    private static ?AppAdapter $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): AppAdapter
    {
        if (self::$instance === null) {
            self::$instance = new AppAdapter();
        }

        return self::$instance;
    }

    public function isDevelopeMode(): bool
    {
        return app()->isLocal();
    }

    public function passwordHash(string $password): string
    {
        return Hash::make($password);
    }

    public function uuid7Generate(?DateTimeInterface $dateTime = null): UuidInterface
    {
        return Uuid::uuid7($dateTime);
    }

    public function uuidFromString(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }
}
