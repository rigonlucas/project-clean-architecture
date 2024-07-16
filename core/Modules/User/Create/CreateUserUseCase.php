<?php

namespace Core\Modules\User\Create;

use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Create\Exceptions\InvalidAgeException;
use Core\Modules\User\Create\Gateways\CreateUserInterface;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\outputs\CreateUserOutput;
use Exception;

class CreateUserUseCase
{
    private GenericOutput $output;

    public function __construct(
        private CreateUserInterface $createUserInterface
    ) {
    }

    public function execute(CreateUserInput $createUserInput): void
    {
        try {
            $userEntity = new UserEntity(
                $createUserInput->name,
                $createUserInput->email,
                $createUserInput->password,
                $createUserInput->age
            );

            $userEntity = $this->createUserInterface->create($userEntity);

            $this->output = new CreateUserOutput(
                new OutputStatus(201, 'Ok'),
                $userEntity
            );
        } catch (InvalidAgeException $e) {
            $this->output = new OutputError(
                new OutputStatus(400, 'Bad Request'),
                $e->getMessage()
            );
        } catch (Exception $e) {
            $this->output = new OutputError(
                new OutputStatus(500, 'Internal Server Error'),
                $e->getMessage()
            );
        }
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
