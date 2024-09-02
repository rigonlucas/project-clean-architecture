<?php

namespace Core\Application\Account\Commons\Gateways;

use Core\Domain\Entities\Account\AccountEntity;

interface AccountMapperInterface
{

    public function findByUuid(string $uuid): ?AccountEntity;

    public function findByAccessCode(string $code): ?AccountEntity;
}