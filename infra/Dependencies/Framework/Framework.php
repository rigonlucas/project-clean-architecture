<?php

namespace Infra\Dependencies\Framework;

use Core\Adapters\Framework\Contracts\AuthContract;
use Core\Adapters\Framework\Contracts\UuidContract;
use Core\Adapters\Framework\FrameworkContract;
use Illuminate\Support\Facades\Hash;
use Infra\Dependencies\Framework\Concerns\AuthAdapter;
use Infra\Dependencies\Framework\Concerns\UuidAdapter;

class Framework implements FrameworkContract
{
    private static ?Framework $instance = null;

    private function __construct()
    {
    }

    public function isDevelopeMode(): bool
    {
        return app()->isLocal();
    }

    public function passwordHash(string $password): string
    {
        return Hash::make($password);
    }

    public function auth(): AuthContract
    {
        return AuthAdapter::getInstance();
    }

    public static function getInstance(): Framework
    {
        if (self::$instance === null) {
            self::$instance = new Framework();
        }

        return self::$instance;
    }

    public function uuid(): UuidContract
    {
        return new UuidAdapter();
    }
}
