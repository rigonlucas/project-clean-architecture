<?php

namespace Core\Modules\User\Update;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutput;

class UpdateUserUseCase
{
    private GenericOutput $output;

    public function __construct(
        private readonly AppInterface $app,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    /**
     * @throws InvalidAgeException
     * @throws EmailAlreadyUsedByOtherUserException
     */
    public function execute(UpdateUserInput $input): void
    {
        $userEntity = $this->userRepository->existsId($input->id);
        if (!$userEntity) {
            $this->output = new OutputError(
                new OutputStatus(404, 'Not found'),
                'Usuário não encontrado'
            );
            return;
        }
        $recordedEserEntity = $this->userRepository->findByEmail($input->id);
        if ($recordedEserEntity && $recordedEserEntity->getId() != $input->id) {
            throw new EmailAlreadyUsedByOtherUserException();
        }

        $userEntity = UserEntity::update(
            $input->id,
            $input->name,
            $input->email,
            $this->app->passwordHash($input->password),
            $input->birthday
        );
        $userEntity->validateAge();

        $this->userCommand->update($userEntity);

        $this->output = new UpdateUserOutput(
            new OutputStatus(200, 'Ok'),
            $userEntity
        );
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
