<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UpdateUserRequest;
use Carbon\Carbon;
use Core\Adapters\Framework\Contracts\TransactionManagerInterface;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Infra\Handlers\UseCases\User\Update\UpdateUserHandler;
use Ramsey\Uuid\Uuid;

class UpdateUserController extends Controller
{
    public function __construct(
        private readonly TransactionManagerInterface $transactionManager,
        private readonly UserCommandInterface $userCommandInterface,
        private readonly UserRepositoryInterface $userRepositoryInterface
    ) {
    }

    public function __invoke(UpdateUserRequest $request, string $uuid)
    {
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($uuid),
            name: $request->name,
            email: $request->email,
            password: $request->password,
            birthday: Carbon::createFromFormat('Y-m-d', $request->birthday)
        );

        try {
            $this->transactionManager->beginTransaction();
            $output = (new UpdateUserHandler(
                $this->userCommandInterface,
                $this->userRepositoryInterface
            ))->handle(input: $input);
            $this->transactionManager->commit();
        } catch (OutputErrorException $outputErrorException) {
            $this->transactionManager->rollBack();
            return response()->json(
                data: (new ErrorPresenter(
                    message: 'Contém erros de validação',
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: (new UserPresenter($output->userEntity))->withDataAttribute()->toArray(),
            status: ResponseStatusCodeEnum::OK->value
        );
    }
}
