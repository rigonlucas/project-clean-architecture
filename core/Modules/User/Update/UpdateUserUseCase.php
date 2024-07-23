<?php

namespace Core\Modules\User\Update;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutput;
use Exception;

class UpdateUserUseCase
{
    private GenericOutput $output;

    public function __construct(
        private AppInterface $app,
        private UserRepositoryInterface $userRepository,
        private UserCommandInterface $userCommand
    ) {
    }

    public function execute(UpdateUserInput $input): void
    {
        try {
            $userEntity = $this->userRepository->existsEmail($input->id);
            if (!$userEntity) {
                $this->output = new OutputError(
                    new OutputStatus(404, 'Not found'),
                    'Usuário não encontrado'
                );
                return;
            }
            $userEntity = UserEntity::update(
                $input->id,
                $input->name,
                $input->email,
                $input->password,
                $input->birthday
            );

            $this->userCommand->update($userEntity);

            $this->output = new UpdateUserOutput(
                new OutputStatus(200, 'Ok'),
                $userEntity
            );
        } catch (InvalidAgeException $e) {
            $this->output = new OutputError(
                new OutputStatus(400, 'Bad Request'),
                $e->getMessage(),
                $e->getTrace(),
                $this->app->isDevelopeMode()
            );
        } catch (Exception $e) {
            $this->output = new OutputError(
                new OutputStatus(500, 'Internal Server Error'),
                $e->getMessage(),
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
