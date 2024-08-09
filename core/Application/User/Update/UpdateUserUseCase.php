<?php

namespace Core\Application\User\Update;

use Core\Adapters\Framework\AppContract;
use Core\Application\User\Commons\Entities\User\UserEntity;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\Output\UpdateUserOutput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class UpdateUserUseCase
{
    use HasErrorBagTrait;


    public function __construct(
        private readonly AppContract $app,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(UpdateUserInput $input): UpdateUserOutput
    {
        $recordedUser = $this->userRepository->findByUuid(uuid: $input->uuid);
        if (!$recordedUser) {
            throw new OutputErrorException(
                message: 'Contém erros de validação',
                code: ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                errors: $this->getErrorBag()
            );
        }
        if ($recordedUser->getEmail() != $input->email) {
            $recordedUserByEmail = $this->userRepository->findByEmail(email: $input->email);
            if ($recordedUserByEmail && $recordedUserByEmail->getUuid()->toString() != $input->uuid) {
                $this->addError('email', 'Email já utilizado por outro usuário');
            }
        }

        $userEntity = UserEntity::forUpdate(
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

        $this->checkValidationErrors();

        $userEntity->setUuid($recordedUser->getUuid());
        $this->userCommand->update($userEntity);

        return new UpdateUserOutput($userEntity);
    }
}
