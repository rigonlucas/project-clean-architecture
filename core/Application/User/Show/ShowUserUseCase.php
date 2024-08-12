<?php

namespace Core\Application\User\Show;

use Core\Adapters\Framework\FrameworkContract;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Domain\Entities\User\UserEntity;
use Core\Tools\Http\ResponseStatusCodeEnum;

class ShowUserUseCase
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws UserNotFountException
     */
    public function execute(int $id,): UserEntity
    {
        $userEntity = $this->userRepository->findById($id);
        if (!$userEntity) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatusCodeEnum::NOT_FOUND->value,
            );
        }

        $this->framework->auth()->userAccountsIds();

        if ($userEntity) {
        }

        return $userEntity;
    }
}
