<?php

namespace Infra\Database\Account\Repository;

use App\Models\Account;
use App\Models\AccountJoinCode;
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

    public function findByAccessCode(string $code): ?AccountEntity
    {
        $accountJoin = AccountJoinCode::query()
            ->where('code', '=', $code)
            ->whereNull('user_id')
            ->where('expired_at', '>', now())
            ->with(['account:id,name,uuid'])
            ->first();
        if (!$accountJoin) {
            return null;
        }

        return AccountEntity::forDetail(
            id: $accountJoin->account->id,
            name: $accountJoin->account->name,
            uuid: $accountJoin->account->uuid,

        );
    }
}
