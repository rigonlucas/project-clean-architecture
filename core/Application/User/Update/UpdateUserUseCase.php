<?php

namespace Core\Application\User\Update;

use Core\Adapters\Framework\FrameworkContract;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Domain\Entities\User\UserEntity;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class UpdateUserUseCase
{
    use HasErrorBagTrait;


    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(UpdateUserInput $input): UserEntity
    {
        $recordedUser = $this->userRepository->findByUuid(uuid: $input->uuid);
        if (!$recordedUser) {
            throw new UserNotFountException(
                message: 'Contém erros de validação',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
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
            password: $this->framework->passwordHash($input->password),
            birthday: $input->birthday
        );

        $hasNoLegalAge = $userEntity->underAge();
        if ($hasNoLegalAge) {
            $this->addError('birthday', 'Idade deve ser maior que 18 anos');
        }

        $this->checkValidationErrors();

        $userEntity->setUuid($recordedUser->getUuid());
        return $this->userCommand->update($userEntity);
    }
}
