<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Dependencies\Framework\Framework;

class UpdateUserHandler
{
    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            Framework::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        return new UpdateUserOutput($useCase->execute($input));
    }
}
