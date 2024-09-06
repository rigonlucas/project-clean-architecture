<?php

namespace Core\Application\User\Update;

use Core\Application\User\Shared\Exceptions\UserNotFountException;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Validations\HasErrorBagTrait;

class UpdateUserUseCase
{
    use HasErrorBagTrait;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserMapperInterface $userMapper,
        private readonly UserCommandInterface $userCommand
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function execute(UpdateUserInput $input): UserEntity
    {
        $input->authenticableUser->canUpateAnUser($input->uuid);

        $recordedUser = $this->userMapper->findByUuid(uuid: $input->uuid);
        if (!$recordedUser) {
            throw new UserNotFountException(
                message: 'Validations errors found',
                code: ResponseStatus::NOT_FOUND->value
            );
        }

        if ($recordedUser->getEmail()->get() !== $input->email->get()) {
            $recordedUserByEmail = $this->userMapper->findByEmail($input->email);
            if ($recordedUserByEmail) {
                $this->addError('email', 'The email has already been taken.');
            }
        }

        $userEntity = UserEntity::forUpdate(
            uuid: $recordedUser->getUuid(),
            name: $input->name,
            email: $input->email,
            password: $this->framework->passwordHash($input->password),
            birthday: $input->birthday
        );

        $hasNoLegalAge = $userEntity->underAge();
        if ($hasNoLegalAge) {
            $this->addError('birthday', 'Age is less than 18 years');
        }

        $this->checkValidationErrors();

        $userEntity->setUuid($recordedUser->getUuid());
        return $this->userCommand->update($userEntity);
    }
}
