<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\AccountInput;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\Account\Command\AccountCommand;
use Infra\Database\Account\Repository\AccountRepository;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;

class CreateUserHandler
{

    /**
     * @throws OutputErrorException
     */
    public function handle(CreateUserInput $createUserInput, ?AccountInput $accountInput): CreateUserOutput
    {
        $useCase = new CreateUserUseCase(
            FrameworkService::getInstance(),
            new UserCommand(),
            new UserRepository(),
            new AccountCommand(),
            new AccountRepository()
        );
        $userEntity = $useCase->execute($createUserInput, $accountInput);

        return new CreateUserOutput($userEntity);
    }
}
