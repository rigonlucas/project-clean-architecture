<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateUserRequest;
use Carbon\Carbon;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Services\Framework\FrameworkContract;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Infra\Handlers\UseCases\User\Create\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __construct(
        private readonly TransactionManagerContract $transactionManager,
        private readonly UserCommandInterface $userCommandInterface,
        private readonly UserRepositoryInterface $userRepositoryInterface,
        private readonly AccountCommandInterface $accountCommandInterface,
        private readonly AccountRepositoryInterface $accountRepositoryInterface,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    public function __invoke(CreateUserRequest $request)
    {
        $createUserInput = new CreateUserInput(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            birthday: Carbon::createFromFormat('Y-m-d', $request->birthday)
        );

        $accountInput = new AccountInput(
            name: $request->name,
            accessCode: $request->account_access_code
        );
        try {
            $this->transactionManager->beginTransaction();
            $output = (new CreateUserHandler(
                userCommandInterface: $this->userCommandInterface,
                userRepositoryInterface: $this->userRepositoryInterface,
                accountCommandInterface: $this->accountCommandInterface,
                accountRepositoryInterface: $this->accountRepositoryInterface,
                frameworkService: $this->frameworkService
            ))->handle(
                createUserInput: $createUserInput,
                accountInput: $accountInput
            );
            $this->transactionManager->commit();
        } catch (OutputErrorException $outputErrorException) {
            $this->transactionManager->rollBack();
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: (new UserPresenter($output->userEntity))->withDataAttribute()->toArray(),
            status: ResponseStatusCodeEnum::CREATED->value
        );
    }
}
