<?php

namespace Core\Application\User\Create;

use Core\Adapters\Framework\AppContract;
use Core\Application\User\Commons\Entities\User\UserEntity;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Application\User\Create\Output\CreateUserOutput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Support\HasErrorBagTrait;

class CreateUserUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly AppContract $app,
        private readonly UserCommandInterface $createUserInterface,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(CreateUserInput $createUserInput): CreateUserOutput
    {
        $emailAlreadyExists = $this->userRepository->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            $this->addError('email', 'Email já utilizado por outro usuário');
        }
        $userEntity = UserEntity::create(
            name: $createUserInput->name,
            email: $createUserInput->email,
            password: $this->app->passwordHash($createUserInput->password),
            uuid: $this->app->uuid7Generate(),
            birthday: $createUserInput->birthday
        );

        $hasNoLegalAge = $userEntity->hasNoLegalAge();
        if ($hasNoLegalAge) {
            $this->addError('birthday', 'Idade inválida');
        }
        $this->hasErrorBag();

        $userEntity = $this->createUserInterface->create($userEntity);
        return new CreateUserOutput(userEntity: $userEntity);
    }
}
