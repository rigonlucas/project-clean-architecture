<?php

namespace Infra\Services\Framework\Adapters;

use Core\Services\Framework\Contracts\TransactionManagerContract;
use Illuminate\Support\Facades\DB;

class TransactionManagerAdapter implements TransactionManagerContract
{

    private static ?TransactionManagerAdapter $instance = null;

    public static function getInstance(): TransactionManagerAdapter
    {
        if (self::$instance === null) {
            self::$instance = new TransactionManagerAdapter();
        }

        return self::$instance;
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(): void
    {
        DB::rollBack();
    }
}
