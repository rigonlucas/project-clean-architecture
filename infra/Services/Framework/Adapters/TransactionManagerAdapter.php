<?php

namespace Infra\Services\Framework\Adapters;

use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Support\HasSingletonTrait;
use Illuminate\Support\Facades\DB;

class TransactionManagerAdapter implements TransactionManagerContract
{
    use HasSingletonTrait;

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
