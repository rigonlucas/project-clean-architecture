<?php

namespace Infra\Database\Account\Mapper;

use App\Models\Account;
use App\Models\AccountJoinCode;
use Core\Application\Account\Commons\Gateways\AccountMapperInterface;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\Account\AccountJoinCodeEntity;
use Ramsey\Uuid\Uuid;

class AccountMapper implements AccountMapperInterface
{
    public function findByid(int $id): ?AccountEntity
    {
        $accountModel = Account::query()
            ->select(['id', 'name', 'uuid'])
            ->where('id', $id)
            ->first();
        if (!$accountModel) {
            return null;
        }

        return AccountEntity::forDetail(
            id: $accountModel->id,
            name: $accountModel->name,
            uuid: Uuid::fromString($accountModel->uuid)
        );
    }

    public function findByUuid(string $uuid): ?AccountEntity
    {
        $accountModel = Account::query()
            ->select(['id', 'name', 'uuid'])
            ->where('uuid', $uuid)
            ->first();
        if (!$accountModel) {
            return null;
        }

        return AccountEntity::forDetail(
            id: $accountModel->id,
            name: $accountModel->name,
            uuid: Uuid::fromString($accountModel->uuid)
        );
    }

    public function findByAccessCode(string $code): ?AccountEntity
    {
        $accountJoin = AccountJoinCode::query()
            ->select(['id', 'code', 'account_id', 'expired_at'])
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
            uuid: Uuid::fromString($accountJoin->account->uuid),
        );

        return $accountEntity->setJoinCodeEntity($accountJoinEntity);
    }
}
