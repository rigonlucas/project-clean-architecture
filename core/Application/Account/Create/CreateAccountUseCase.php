<?php

namespace Core\Application\Account\Create;

use Core\Application\Account\Commons\Exceptions\AccountNameInvalidException;
use Core\Application\Account\Commons\Exceptions\AccountNotFoundException;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountMapperInterface;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Http\ResponseStatus;
use Core\Support\Validations\HasErrorBagTrait;

class CreateAccountUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly AccountCommandInterface $accountCommand,
        private readonly AccountMapperInterface $accountMapper
    ) {
    }

    /**
     * @throws AccountNotFoundException
     * @throws AccountNameInvalidException
     */
    public function execute(AccountInput $input): AccountEntity
    {
        $accountEntity = $this->processAccountCreation($input);

        $userEntity = UserEntity::forIdentify($input->getUserUuid());
        $accountEntity->setUserEntity($userEntity);

        if ($accountEntity->getJoinCodeEntity()) {
            $this->accountCommand->useAccountJoinCode($accountEntity);

            return $accountEntity;
        }

        return $this->accountCommand->createAccount($accountEntity);
    }

    /**
     * @throws AccountNotFoundException
     * @throws AccountNameInvalidException
     */
    private function processAccountCreation(AccountInput $input): AccountEntity
    {
        if (is_null($input->accessCode)) {
            return $this->createNewAccount($input);
        }

        return $this->findAnAccountByAccessCode($input);
    }

    /**
     * @throws AccountNameInvalidException
     */
    private function createNewAccount(AccountInput $input): AccountEntity
    {
        return AccountEntity::forCreate(
            name: $this->framework->str()->title($input->getUserNane()),
            uuid: $this->framework->uuid()->uuid7Generate()
        );
    }

    /**
     * @throws AccountNotFoundException
     */
    private function findAnAccountByAccessCode(AccountInput $accountInput): AccountEntity
    {
        $accountEntity = $this->accountMapper->findByAccessCode($accountInput->accessCode);
        if (is_null($accountEntity)) {
            throw new AccountNotFoundException(
                message: 'Account join code not found',
                code: ResponseStatus::NOT_FOUND->value
            );
        }

        return $accountEntity;
    }

}
