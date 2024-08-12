<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Application\User\Create\CreateUserUseCase;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Dependencies\Framework\Framework;

class CreateUserHandler
{

    /**
     * @throws OutputErrorException
     */
    public function handle(CreateUserInput $createUserInput): CreateUserOutput
    {
        $useCase = new CreateUserUseCase(
            Framework::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $userEntity = $useCase->execute($createUserInput);

        return new CreateUserOutput($userEntity);
    }
}
