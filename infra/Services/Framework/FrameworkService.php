<?php

namespace Infra\Services\Framework;

use Core\Adapters\Framework\Contracts\AuthContract;
use Core\Adapters\Framework\Contracts\TransactionManagerInterface;
use Core\Adapters\Framework\Contracts\UuidContract;
use Core\Adapters\Framework\FrameworkContract;
use Illuminate\Support\Facades\Hash;
use Infra\Services\Framework\Adapters\AuthAdapter;
use Infra\Services\Framework\Adapters\TransactionManagerAdapter;
use Infra\Services\Framework\Adapters\UuidAdapter;

class FrameworkService implements FrameworkContract
{
    private static ?FrameworkService $instance = null;

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

    public static function getInstance(): FrameworkService
    {
        if (self::$instance === null) {
            self::$instance = new FrameworkService();
        }

        return self::$instance;
    }

    public function transactionManager(): TransactionManagerInterface
    {
        return TransactionManagerAdapter::getInstance();
    }

    public function uuid(): UuidContract
    {
        return UuidAdapter::getInstance();
    }
}
