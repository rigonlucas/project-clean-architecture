<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\AccountInput;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Services\Framework\FrameworkService;

readonly class CreateUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommandInterface,
        private UserRepositoryInterface $userRepositoryInterface,
        private AccountCommandInterface $accountCommandInterface,
        private AccountRepositoryInterface $accountRepositoryInterface
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function handle(CreateUserInput $createUserInput, ?AccountInput $accountInput): CreateUserOutput
    {
        $useCase = new CreateUserUseCase(
            FrameworkService::getInstance(),
            $this->userCommandInterface,
            $this->userRepositoryInterface,
            $this->accountCommandInterface,
            $this->accountRepositoryInterface
        );
        $userEntity = $useCase->execute($createUserInput, $accountInput);

        return new CreateUserOutput($userEntity);
    }
}
