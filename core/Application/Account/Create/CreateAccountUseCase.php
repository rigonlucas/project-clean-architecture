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
    public function execute(AccountInput $input): AccountEntity
    {
        $accountEntity = $this->processAccount($input);

        $userEntity = UserEntity::forIdentify($input->getUserId());
        $accountEntity->setUserEntity($userEntity);

        if ($accountEntity->getJoinCodeEntity()) {
            $this->accountCommand->useAccountJoinCode($accountEntity);

            return $accountEntity;
        }

        return $this->accountCommand->createAccount($accountEntity);
    }

    /**
     * @throws AccountNotFoundException
     */
    private function processAccount(AccountInput $input): ?AccountEntity
    {
        if (is_null($input->accessCode)) {
            return $this->createNewAccount($input);
        }

        return $this->findAnAccount($input);
    }

    private function createNewAccount(AccountInput $input): AccountEntity
    {
        return AccountEntity::forCreate(
            name: $input->getUserNane(),
            uuid: $this->framework->uuid()->uuid7Generate()
        );
    }

    /**
     * @throws AccountNotFoundException
     */
    private function findAnAccount(AccountInput $accountInput): AccountEntity
    {
        $accountEntity = $this->accountRepository->findByAccessCode($accountInput->accessCode);
        if (is_null($accountEntity)) {
            throw new AccountNotFoundException(
                message: 'Account join code not found, expired or invalid',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
            );
        }

        return $accountEntity;
    }

}
