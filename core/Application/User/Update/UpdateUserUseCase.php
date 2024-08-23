<?php

namespace Core\Application\User\Update;

use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\ForbidenException;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Core\Support\Permissions\UserRoles;
use Core\Support\Validations\HasErrorBagTrait;

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
        if (!filter_var($input->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Invalid email.');
        }
        $recordedUser = $this->userRepository->findByUuid(uuid: $input->uuid);
        if (!$recordedUser) {
            throw new UserNotFountException(
                message: 'Contém erros de validação',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
            );
        }
        if (
            !$this->framework->auth()->user()->getUuid()->equals($input->uuid) &&
            !$this->framework->auth()->user()->hasNotPermission(UserRoles::ADMIN)
        ) {
            throw new ForbidenException(
                message: 'Você não tem permissão para alterar este usuário',
                code: ResponseStatusCodeEnum::FORBIDDEN->value
            );
        }

        if ($recordedUser->getEmail()->getEmail() !== $input->email->getEmail()) {
            $recordedUserByEmail = $this->userRepository->findByEmail($input->email);
            if ($recordedUserByEmail && !$recordedUserByEmail->getUuid()->equals($input->uuid)) {
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
