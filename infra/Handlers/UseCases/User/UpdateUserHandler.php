<?php

namespace Infra\Handlers\UseCases\User;

use Core\Adapters\App\AppAdapter;
use Core\Generics\Outputs\OutputError;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Exceptions\EmailAlreadyUsedByOtherUserException;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\UpdateUserUseCase;
use Core\Support\HasGenericOutputTrait;
use Exception;
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
        } catch (EmailAlreadyUsedByOtherUserException $emailAlreadyUsedByOtherUserException) {
            $this->output = new OutputError(
                status: new OutputStatus(422, 'Unprocessable Entity'),
                message: 'Email já utilizado por outro usuário',
            );
        } catch (InvalidAgeException $invalidAgeException) {
            $this->output = new OutputError(
                status: new OutputStatus(400, 'Bad Request'),
                message: 'Idade inválida',
            );
        } catch (Exception $exception) {
            $this->output = new OutputError(
                status: new OutputStatus(500, 'Internal Server Error'),
                message: $exception->getMessage(),
                trace: $exception->getTrace(),
                isDevelopementMode: app()->isDevelopeMode()
            );
        }

        return $this;
    }
}
