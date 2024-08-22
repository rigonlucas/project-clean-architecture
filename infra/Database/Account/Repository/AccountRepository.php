<?php

namespace Infra\Database\Account\Repository;

use App\Models\Account;
use App\Models\AccountJoinCode;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Account\AccountJoinCodeEntity;

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
            ->with(['account:id,name,uuid'])
            ->first();
        if (!$accountJoin) {
            return null;
        }
        $accountJoinEntity = AccountJoinCodeEntity::forDetail(
            id: $accountJoin->id,
            code: $accountJoin->code,
            accountid: $accountJoin->account_id,
            expiresAt: $accountJoin->expired_at
        );
        $accountEntity = AccountEntity::forDetail(
            id: $accountJoin->account->id,
            name: $accountJoin->account->name,
            uuid: $accountJoin->account->uuid,
        );

        return $accountEntity->setJoinCodeEntity($accountJoinEntity);
    }
}
