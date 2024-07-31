<?php

namespace Core\Modules\User\Create;

use Core\Adapters\App\AppInterface;
use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\Output\CreateUserOutput;
use Core\Modules\User\Create\Output\CreateUserOutputError;
use Core\Support\HasErrorBagTrait;
use Core\Tools\Http\ResponseStatusCodeEnum;

class CreateUserUseCase
{
    use HasErrorBagTrait;

    private GenericOutput $output;

    public function __construct(
        private readonly AppInterface $app,
        private readonly UserCommandInterface $createUserInterface,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function execute(CreateUserInput $createUserInput): void
    {
        $emailAlreadyExists = $this->userRepository->existsEmail($createUserInput->email);
        if ($emailAlreadyExists) {
            $this->addError('email', 'Email já utilizado por outro usuário');
        }
        $userEntity = UserEntity::create(
            name: $createUserInput->name,
            email: $createUserInput->email,
            password: $this->app->passwordHash($createUserInput->password),
            uuid: $this->app->uuid7Generate(),
            birthday: $createUserInput->birthday
        );

        $hasNoLegalAge = $userEntity->hasNoLegalAge();
        if ($hasNoLegalAge) {
            $this->addError('birthday', 'Idade inválida');
        }

        if ($this->hasErrorBag()) {
            $this->output = new CreateUserOutputError(
                new OutputStatus(
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->value,
                    ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY->name
                ),
                $this->getErrorBag()
            );
            return;
        }

        $userEntity = $this->createUserInterface->create($userEntity);
        $this->output = new CreateUserOutput(
            new OutputStatus(ResponseStatusCodeEnum::CREATED->value, ResponseStatusCodeEnum::CREATED->name),
            $userEntity
        );
    }

    public function getOutput(): GenericOutput
    {
        return $this->output;
    }
}
