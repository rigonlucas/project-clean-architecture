<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;

class UpdateUserHandler
{
    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        return new UpdateUserOutput($useCase->execute($input));
    }
}
