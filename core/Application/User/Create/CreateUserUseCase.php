<?php

namespace Core\Application\User\Create;

use Core\Adapters\Framework\FrameworkContract;
use Core\Application\Account\Commons\Exceptions\AccountNotFoundException;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Create\Inputs\AccountInput;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class CreateUserUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserCommandInterface $createUserInterface,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AccountCommandInterface $accountCommandInterface,
        private readonly AccountRepositoryInterface $accountRepository
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(CreateUserInput $createUserInput, ?AccountInput $accountInput): UserEntity
    {
        $this->processEmail($createUserInput);

        $userEntity = UserEntity::forCreate(
            name: $createUserInput->name,
            email: $createUserInput->email,
            password: $this->framework->passwordHash($createUserInput->password),
            account: null,
            uuid: $this->framework->uuid()->uuid7Generate(),
            birthday: $createUserInput->birthday
        );

        $isUnderAge = $userEntity->underAge();
        if ($isUnderAge) {
            $this->addError('birthday', 'Idade inválida');
        }

        $accountEntity = $this->processAccount($accountInput);

        if ($accountEntity->getId() === null) {
            $accountEntity = $this->accountCommandInterface->createAccount($accountEntity);
        }

        $userEntity->setAccount($accountEntity);

        $this->checkValidationErrors();

        $this->accountCommandInterface->useAccountJoinCode($accountEntity, $userEntity);

        return $this->createUserInterface->create($userEntity);
    }

    private function processEmail(CreateUserInput $createUserInput): void
    {
        $emailAlreadyExists = $this->userRepository->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            $this->addError('email', 'Email já utilizado por outro usuário');
        }
    }

    /**
     * @throws AccountNotFoundException
     */
    private function processAccount(?AccountInput $accountInput): AccountEntity
    {
        if (!is_null($accountInput->name)) {
            return $this->createNewAccount($accountInput);
        }

        return $this->findAnAccount($accountInput);
    }

    private function createNewAccount(?AccountInput $accountInput): AccountEntity
    {
        return AccountEntity::forCreate(
            name: $accountInput->name,
            uuid: $this->framework->uuid()->uuid7Generate()
        );
    }

    /**
     * @throws AccountNotFoundException
     */
    private function findAnAccount(?AccountInput $accountInput): AccountEntity
    {
        $accountEntity = $this->accountRepository->findByAccessCode($accountInput->accessCode);
        if (!$accountEntity) {
            throw new AccountNotFoundException(
                message: 'Account join code not found, expired or invalid',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
            );
        }
        if (!$accountEntity->getJoinCodeEntity()->isCodeValid()) {
            $this->addError('account', 'Account join code is invalid');
        }

        return $accountEntity;
    }
}
