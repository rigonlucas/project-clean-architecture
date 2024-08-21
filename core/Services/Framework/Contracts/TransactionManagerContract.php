<?php

namespace Core\Services\Framework\Contracts;

interface TransactionManagerContract
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}