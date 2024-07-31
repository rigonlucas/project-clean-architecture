<?php

namespace Infra\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Create\CreateUserUseCase;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutputError;
use Core\Support\HasGenericOutputTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class CreateUserHandler
{
    use  HasGenericOutputTrait;

    public function handle(CreateUserInput $input): self
    {
        try {
            $useCase = new CreateUserUseCase(
                AppAdapter::getInstance(),
                new UserCommand(),
                new UserRepository()
            );
            $useCase->execute($input);
            $this->output = $useCase->getOutput();
        } catch (EmailAlreadyUsedByOtherUserException $emailAlreadyUsedByOtherUserException) {
            $this->output = new CreateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                ['email' => ['Email já utilizado por outro usuário']]
            );
        } catch (InvalidAgeException $invalidAgeException) {
            $this->output = new CreateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                ['birthday' => ['Idade inválida']]
            );
        }

        return $this;
    }
}
