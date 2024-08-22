<?php

namespace Infra\Services\Framework;

use Core\Services\Framework\Contracts\AuthContract;
use Core\Services\Framework\Contracts\StrContract;
use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Services\Framework\Contracts\UuidContract;
use Core\Services\Framework\FrameworkContract;
use Core\Support\HasSingletonTrait;
use Illuminate\Support\Facades\Hash;
use Infra\Services\Framework\Adapters\AuthAdapter;
use Infra\Services\Framework\Adapters\StrAdapter;
use Infra\Services\Framework\Adapters\TransactionManagerAdapter;
use Infra\Services\Framework\Adapters\UuidAdapter;

class FrameworkService implements FrameworkContract
{
    use HasSingletonTrait;

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

    public function transactionManager(): TransactionManagerContract
    {
        return TransactionManagerAdapter::getInstance();
    }

    public function uuid(): UuidContract
    {
        return UuidAdapter::getInstance();
    }

    public function Str(): StrContract
    {
        return StrAdapter::getInstance();
    }
}
