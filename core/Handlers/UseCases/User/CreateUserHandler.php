<?php

namespace Core\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Application\User\Create\Output\CreateUserOutput;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;

class CreateUserHandler
{

    /**
     * @throws OutputErrorException
     */
    public function handle(CreateUserInput $createUserInput): CreateUserOutput
    {
        $useCase = new CreateUserUseCase(
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        return $useCase->execute($createUserInput);
    }
}
