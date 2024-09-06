<?php

namespace Core\Application\Account\Commons\Gateways;

use Core\Domain\Entities\Shared\Account\Root\AccountEntity;

interface AccountMapperInterface
{

    public function findByUuid(string $uuid): ?AccountEntity;

    public function findByAccessCode(string $code): ?AccountEntity;
}