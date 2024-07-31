<?php

namespace Core\Modules\User\Update;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutput;
use Core\Modules\User\Update\Output\UpdateUserOutputError;
use Core\Tools\Http\ResponseStatusCodeEnum;

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
        $recordedUser = $this->userRepository->findById($input->id);
        if (!$recordedUser) {
            $this->output = new UpdateUserOutputError(
                new OutputStatus(404, 'Not found'),
                'Usuário não encontrado'
            );
            return;
        }
        $recordedUserByEmail = $this->userRepository->findByEmail($input->email);
        if ($recordedUserByEmail && $recordedUserByEmail->getId() != $input->id) {
            throw new EmailAlreadyUsedByOtherUserException();
        }

        $userEntity = UserEntity::update(
            id: $input->id,
            name: $input->name,
            email: $input->email,
            password: $this->app->passwordHash($input->password),
            birthday: $input->birthday
        );
        $userEntity->setUuid($recordedUser->getUuid());
        $this->userCommand->update($userEntity);

        $this->output = new UpdateUserOutput(
            new OutputStatus(ResponseStatusCodeEnum::OK->value, ResponseStatusCodeEnum::OK->name),
            $userEntity
        );
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
