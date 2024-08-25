<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountMapperInterface;
use Core\Application\Account\Create\CreateAccountUseCase;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;

readonly class CreateUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserMapperInterface $userMapper,
        private AccountCommandInterface $accountCommand,
        private AccountMapperInterface $accountMapper,
        private FrameworkContract $frameworkService
    ) {
    }

    /**
     * @throws OutputErrorException
     */
    public function handle(CreateUserInput $createUserInput, AccountInput $accountInput): CreateUserOutput
    {
        $createUserUseCase = new CreateUserUseCase(
            framework: $this->frameworkService,
            userCommand: $this->userCommand,
            userMapper: $this->userMapper
        );
        $userEntity = $createUserUseCase->execute(createUserInput: $createUserInput);

        $createAccountUseCase = new CreateAccountUseCase(
            framework: $this->frameworkService,
            accountCommand: $this->accountCommand,
            accountMapper: $this->accountMapper
        );

        $accountInput->setUserNane(userNane: $userEntity->getName());
        $accountInput->setUserId(userId: $userEntity->getId());

        $accountEntity = $createAccountUseCase->execute(input: $accountInput);

        return new CreateUserOutput(userEntity: $userEntity, accountEntity: $accountEntity);
    }
}
