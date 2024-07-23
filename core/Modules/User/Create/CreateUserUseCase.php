<?php

namespace Core\Modules\User\Create;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutput;
use Exception;

class CreateUserUseCase
{
    private GenericOutput $output;

    public function __construct(
        private AppInterface $app,
        private UserCommandInterface $createUserInterface,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function execute(CreateUserInput $createUserInput): void
    {
        try {
            $userEntity = $this->userRepository->existsEmail($createUserInput->email);
            if ($userEntity) {
                $this->output = new OutputError(
                    new OutputStatus(403, 'Forbidden'),
                    'Usuário encontrado'
                );
                return;
            }
            $userEntity = UserEntity::create(
                $createUserInput->name,
                $createUserInput->email,
                $createUserInput->password,
                $createUserInput->birthday
            );
            $userEntity->validateAge();

            $userEntity = $this->createUserInterface->create($userEntity);

            $this->output = new CreateUserOutput(
                new OutputStatus(201, 'Ok'),
                $userEntity
            );
        } catch (InvalidAgeException $e) {
            $this->output = new OutputError(
                new OutputStatus(400, 'Bad Request'),
                'Idade inválida',
                $e->getTrace(),
                $this->app->isDevelopeMode()
            );
        } catch (Exception $e) {
            $this->output = new OutputError(
                new OutputStatus(500, 'Internal Server Error'),
                'Erro interno do servidor',
                $e->getTrace(),
                $this->app->isDevelopeMode()
            );
        }
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
