<?php

namespace Core\Modules\User\Update;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Create\Output\CreateUserOutputError;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutput;
use Core\Modules\User\Update\Output\UpdateUserOutputError;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class UpdateUserUseCase
{
    use HasErrorBagTrait;

    private GenericOutput $output;

    public function __construct(
        private readonly AppInterface $app,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    /**
     * @param UpdateUserInput $input
     * @throws EmailAlreadyUsedByOtherUserException
     */
    public function execute(UpdateUserInput $input): void
    {
        $recordedUser = $this->userRepository->findByUuid($input->uuid);
        if (!$recordedUser) {
            $this->output = new UpdateUserOutputError(
                new OutputStatus(404, 'Not found'),
                ['name' => 'Usuário não encontrado']
            );
            return;
        }
        $recordedUserByEmail = $this->userRepository->findByEmail($input->email);
        if ($recordedUserByEmail && $recordedUserByEmail->getUuid()->toString() != $input->uuid) {
            $this->addError('email', 'Email já utilizado por outro usuário');
        }

        $userEntity = UserEntity::update(
            id: $recordedUser->getId(),
            name: $input->name,
            email: $input->email,
            password: $this->app->passwordHash($input->password),
            birthday: $input->birthday
        );

        $hasNoLegalAge = $userEntity->hasNoLegalAge();
        if ($hasNoLegalAge) {
            $this->addError('birthday', 'Idade inválida');
        }

        if ($this->hasErrorBag()) {
            $this->output = new CreateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                $this->getErrorBag()
            );
            return;
        }

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
