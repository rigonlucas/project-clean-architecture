<?php

namespace Core\Services\Framework;

use Core\Services\Framework\Contracts\AuthContract;
use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Services\Framework\Contracts\UuidContract;

/**
 * Interface FrameworkContract is a contract for the framework adapter.
 * This contract is used to define the methods that the framework adapter must implement.
 */
interface FrameworkContract
{
    public function auth(): AuthContract;

    public function uuid(): UuidContract;

    public function transactionManager(): TransactionManagerContract;

    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;
}