<?php

namespace Infra\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\UpdateUserUseCase;
use Core\Support\HasGenericOutputTrait;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class UpdateUserHandler
{
    use HasGenericOutputTrait;

    public function handle(UpdateUserInput $input): self
    {
        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $useCase->execute($input);
        $this->output = $useCase->getOutput();

        return $this;
    }
}
