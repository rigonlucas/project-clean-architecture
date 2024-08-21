<?php

namespace Core\Application\Account\Create;

use Core\Application\Account\Commons\Exceptions\AccountNotFoundException;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class CreateAccountUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly AccountCommandInterface $accountCommand,
        private readonly AccountRepositoryInterface $accountRepository
    ) {
    }

    /**
     * @throws AccountNotFoundException
     */
    public function execute(AccountInput $input, UserEntity $userEntity): AccountEntity
    {
        $accountEntity = $this->processAccount($input, $userEntity);
        if ($accountEntity->getJoinCodeEntity()) {
            $this->accountCommand->useAccountJoinCode($accountEntity, $userEntity);
            return $accountEntity;
        }

        return $this->accountCommand->createAccount($accountEntity, $userEntity);
    }

    /**
     * @throws AccountNotFoundException
     */
    private function processAccount(AccountInput $input, UserEntity $userEntity): ?AccountEntity
    {
        if (is_null($input->accessCode)) {
            return $this->createNewAccount($userEntity);
        }

        return $this->findAnAccount($input);
    }

    private function createNewAccount(UserEntity $userEntity): AccountEntity
    {
        return AccountEntity::forCreate(
            name: $userEntity->getName(),
            uuid: $this->framework->uuid()->uuid7Generate()
        );
    }

    /**
     * @throws AccountNotFoundException
     */
    private function findAnAccount(AccountInput $accountInput): AccountEntity
    {
        $accountEntity = $this->accountRepository->findByAccessCode($accountInput->accessCode);
        if (!$accountEntity) {
            throw new AccountNotFoundException(
                message: 'Account join code not found, expired or invalid',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
            );
        }

        return $accountEntity;
    }

}
