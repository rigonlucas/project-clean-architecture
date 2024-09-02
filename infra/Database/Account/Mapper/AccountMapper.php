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
    public function findByUuid(string $uuid): ?AccountEntity
    {
        $accountModel = Account::query()
            ->select(['name', 'uuid'])
            ->where('uuid', '=', $uuid)
            ->toBase()
            ->first();
        if (!$accountModel) {
            return null;
        }

        return AccountEntity::forDetail(
            uuid: Uuid::fromString($accountModel->uuid),
            name: $accountModel->name
        );
    }

    public function findByAccessCode(string $code): ?AccountEntity
    {
        $accountJoin = AccountJoinCode::query()
            ->select(['uuid', 'code', 'account_uuid', 'expired_at'])
            ->where('code', '=', $code)
            ->whereNull('user_uuid')
            ->with(['account:id,name,uuid'])
            ->first();
        if (!$accountJoin) {
            return null;
        }
        $accountJoinEntity = AccountJoinCodeEntity::forDetail(
            uuid: Uuid::fromString($accountJoin->uuid),
            code: $accountJoin->code,
            accountUuid: Uuid::fromString($accountJoin->account_uuid),
            expiresAt: $accountJoin->expired_at
        );
        $accountEntity = AccountEntity::forDetail(
            uuid: Uuid::fromString($accountJoin->account->uuid),
            name: $accountJoin->account->name,
        );

        return $accountEntity->setJoinCodeEntity($accountJoinEntity);
    }
}
