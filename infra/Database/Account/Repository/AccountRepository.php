<?php

namespace Infra\Database\Account\Repository;

use App\Models\Account;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Domain\Entities\Account\AccountEntity;

class AccountRepository implements AccountRepositoryInterface
{

    public function findByUuid(string $uuid): ?AccountEntity
    {
        $accountModel = Account::query()->where('uuid', $uuid)->first();
        if (!$accountModel) {
            return null;
        }

        return AccountEntity::forDetail(
            id: $accountModel->id,
            name: $accountModel->name,
            uuid: $accountModel->uuid
        );
    }
}
