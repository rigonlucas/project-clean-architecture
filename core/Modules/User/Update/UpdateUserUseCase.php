<?php

namespace Core\Modules\User\Update;

use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\outputs\UpdateUserOutput;
use Exception;

class UpdateUserUseCase
{
    private GenericOutput $output;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserCommandInterface $userCommand
    ) {
    }

    public function execute(UpdateUserInput $input): void
    {
        try {
            $userEntity = $this->userRepository->findById($input->id);
            $userEntity->setId($input->age);
            if (is_null($userEntity)) {
                $this->output = new OutputError(
                    new OutputStatus(404, 'Not found'),
                    'Usuário não encontrado'
                );
                return;
            }

            $userEntity->setPassword($input->password);
            $userEntity->setId($input->id);
            $userEntity->setNome($input->name);
            $userEntity->setEmail($input->email);

            $this->userCommand->update($userEntity);

            $this->output = new UpdateUserOutput(
                new OutputStatus(200, 'Ok'),
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
