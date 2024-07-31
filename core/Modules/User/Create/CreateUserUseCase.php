<?php

namespace Core\Modules\User\Create;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Exceptions\UserNotFoundException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutput;

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
     * @throws UserNotFoundException
     */
    public function execute(CreateUserInput $createUserInput): void
    {
        $userEntity = $this->userRepository->existsEmail($createUserInput->email);
        if ($userEntity) {
            throw new UserNotFoundException();
        }
        $userEntity = UserEntity::create(
            $createUserInput->name,
            $createUserInput->email,
            $this->app->passwordHash($createUserInput->password),
            $createUserInput->birthday
        );
        $userEntity->validateAge();

        $userEntity = $this->createUserInterface->create($userEntity);

        $this->output = new CreateUserOutput(
            new OutputStatus(201, 'Ok'),
            $userEntity
        );
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
