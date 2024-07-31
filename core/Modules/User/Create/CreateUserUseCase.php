<?php

namespace Core\Modules\User\Create;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutput;
use Core\Tools\Http\ResponseStatusCodeEnum;

class CreateUserUseCase
{
    private CreateUserOutput $output;

    public function __construct(
        private readonly AppInterface $app,
        private readonly UserCommandInterface $createUserInterface,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws InvalidAgeException
     * @throws EmailAlreadyUsedByOtherUserException
     */
    public function execute(CreateUserInput $createUserInput): void
    {
        $emailAlreadyExists = $this->userRepository->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            throw new EmailAlreadyUsedByOtherUserException();
        }
        $userEntity = UserEntity::create(
            name: $createUserInput->name,
            email: $createUserInput->email,
            password: $this->app->passwordHash($createUserInput->password),
            uuid: $this->app->uuid5Generate($createUserInput->name),
            birthday: $createUserInput->birthday
        );
        $userEntity = $this->createUserInterface->create($userEntity);

        $this->output = new CreateUserOutput(
            new OutputStatus(ResponseStatusCodeEnum::CREATED->value, ResponseStatusCodeEnum::CREATED->name),
            $userEntity
        );
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
