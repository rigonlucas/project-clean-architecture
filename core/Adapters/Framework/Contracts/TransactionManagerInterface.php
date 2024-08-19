<?php

namespace Core\Adapters\Framework\Contracts;

interface TransactionManagerInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}