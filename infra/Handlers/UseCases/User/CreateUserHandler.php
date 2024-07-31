<?php

namespace Infra\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Create\CreateUserUseCase;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Support\HasGenericOutputTrait;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class CreateUserHandler
{
    use  HasGenericOutputTrait;

    public function handle(CreateUserInput $input): self
    {
        $useCase = new CreateUserUseCase(
            AppAdapter::getInstance(),
            new UserCommand(),
            new UserRepository()
        );
        $useCase->execute($input);
        $this->output = $useCase->getOutput();

        return $this;
    }
}
