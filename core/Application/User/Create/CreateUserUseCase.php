<?php

namespace Core\Application\User\Create;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Validations\HasErrorBagTrait;

class CreateUserUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserCommandInterface $createUserInterface,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(CreateUserInput $createUserInput): UserEntity
    {
        $this->processEmail($createUserInput);

        $userEntity = UserEntity::forCreate(
            name: $this->framework->Str()->title($createUserInput->name),
            email: $createUserInput->email,
            password: $this->framework->passwordHash($createUserInput->password),
            account: null,
            uuid: $this->framework->uuid()->uuid7Generate(),
            birthday: $createUserInput->birthday
        );

        if ($userEntity->getEmail()->isInvalid()) {
            $this->addError('email', 'Invalid email');
        }
        $isUnderAge = $userEntity->underAge();
        if ($isUnderAge) {
            $this->addError('birthday', 'Idade inválida');
        }

        $this->checkValidationErrors();

        return $this->createUserInterface->create($userEntity);
    }

    private function processEmail(CreateUserInput $createUserInput): void
    {
        $emailAlreadyExists = $this->userRepository->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            $this->addError('email', 'Email já utilizado por outro usuário');
        }
    }

}
