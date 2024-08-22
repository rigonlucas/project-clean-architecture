<?php

namespace Core\Application\Account\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;

interface AccountRepositoryInterface
{
    public function findByid(int $id): ?AccountEntity;

    public function findByUuid(string $uuid): ?AccountEntity;

    public function findByAccessCode(string $code): ?AccountEntity;
}