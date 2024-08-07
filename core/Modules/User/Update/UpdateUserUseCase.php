<?php

namespace Core\Modules\User\Update;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutputInterface;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\User\UserEntity;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutputInterface;
use Core\Modules\User\Update\Output\UpdateUserOutputInterfaceError;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class UpdateUserUseCase
{
    use HasErrorBagTrait;


    public function __construct(
        private readonly AppInterface $app,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    public function execute(UpdateUserInput $input): GenericOutputInterface
    {
        $recordedUser = $this->userRepository->findByUuid(uuid: $input->uuid);
        if (!$recordedUser) {
            return new UpdateUserOutputInterfaceError(
                status: new OutputStatus(statusCode: 404, message: 'Not found'),
                message: 'Contém erros de validação',
                errors: ['name' => 'Usuário não encontrado']
            );
        }
        if ($recordedUser->getEmail() != $input->email) {
            $recordedUserByEmail = $this->userRepository->findByEmail(email: $input->email);
            if ($recordedUserByEmail && $recordedUserByEmail->getUuid()->toString() != $input->uuid) {
                $this->addError('email', 'Email já utilizado por outro usuário');
            }
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
            $this->addError('birthday', 'Idade deve ser maior que 18 anos');
        }

        if ($this->hasErrorBag()) {
            return new UpdateUserOutputInterfaceError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                'Contém erros de validação',
                $this->getErrorBag()
            );
        }

        $userEntity->setUuid($recordedUser->getUuid());
        $this->userCommand->update($userEntity);

        return new UpdateUserOutputInterface(
            new OutputStatus(ResponseStatusCodeEnum::OK->value, ResponseStatusCodeEnum::OK->name),
            $userEntity
        );
    }
}
