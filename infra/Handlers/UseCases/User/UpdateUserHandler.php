<?php

namespace Infra\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Exceptions\UserNotFoundException;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutputError;
use Core\Modules\User\Update\UpdateUserUseCase;
use Core\Support\HasGenericOutputTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class UpdateUserHandler
{
    use HasGenericOutputTrait;

    public function handle(UpdateUserInput $input): self
    {
        try {
            $useCase = new UpdateUserUseCase(
                AppAdapter::getInstance(),
                new UserRepository(),
                new UserCommand()
            );
            $useCase->execute($input);
            $this->output = $useCase->getOutput();
        } catch (UserNotFoundException $notFoundException) {
            $this->output = new UpdateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::NOT_FOUND->value,
                    ResponseStatusCodeEnum::NOT_FOUND->name
                ),
                ['name' => ['Usuário encontrado']]
            );
        } catch (EmailAlreadyUsedByOtherUserException $emailAlreadyUsedByOtherUserException) {
            $this->output = new UpdateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                ['email' => ['Email já utilizado por outro usuário']]
            );
        } catch (InvalidAgeException $invalidAgeException) {
            $this->output = new UpdateUserOutputError(
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
