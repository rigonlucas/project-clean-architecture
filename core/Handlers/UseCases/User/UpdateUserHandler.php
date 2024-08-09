<?php

namespace Core\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\Output\UpdateUserOutput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;

class UpdateUserHandler
{
    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        return $useCase->execute($input);
    }
}
