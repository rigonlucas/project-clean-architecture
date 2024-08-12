<?php

namespace Core\Adapters\Framework;

use Core\Adapters\Framework\Contracts\AuthContract;
use Core\Adapters\Framework\Contracts\UuidContract;

/**
 * Interface FrameworkContract is a contract for the framework adapter.
 * This contract is used to define the methods that the framework adapter must implement.
 */
interface FrameworkContract
{
    public function auth(): AuthContract;

    public function uuid(): UuidContract;

    public function isDevelopeMode(): bool;

    public function passwordHash(string $password): string;
}