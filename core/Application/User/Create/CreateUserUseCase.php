<?php

namespace Core\Application\User\Create;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Validations\HasErrorBagTrait;

class CreateUserUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserCommandInterface $userCommand,
        private readonly UserMapperInterface $userMapper
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(CreateUserInput $createUserInput): UserEntity
    {
        $emailAlreadyExists = $this->userMapper->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            $this->addError(key: 'email', message: 'Email já utilizado por outro usuário');
        }

        $userEntity = UserEntity::forCreate(
            name: $this->framework->str()->title($createUserInput->name),
            email: $createUserInput->email,
            password: $this->framework->passwordHash($createUserInput->password),
            account: null,
            uuid: $this->framework->uuid()->uuid7Generate(),
            birthday: $createUserInput->birthday
        );
        $this->processValidations(userEntity: $userEntity);
        $this->checkValidationErrors();

        return $this->userCommand->create(userEntity: $userEntity);
    }

    /**
     * @param UserEntity $userEntity
     * @return void
     */
    private function processValidations(UserEntity $userEntity): void
    {
        if ($userEntity->getEmail()->isInvalid()) {
            $this->addError(key: 'email', message: 'Invalid email');
        }
        $isUnderAge = $userEntity->underAge();
        if ($isUnderAge) {
            $this->addError(key: 'birthday', message: 'Idade inválida');
        }
    }

}
