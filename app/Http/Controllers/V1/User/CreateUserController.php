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
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\User\Create\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __construct(
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
            accessCode: $request->account_access_code
        );
        try {
            $this->frameworkService->transactionManager()->beginTransaction();

            $handler = (new CreateUserHandler(
                userCommandInterface: $this->userCommandInterface,
                userRepositoryInterface: $this->userRepositoryInterface,
                accountCommandInterface: $this->accountCommandInterface,
                accountRepositoryInterface: $this->accountRepositoryInterface,
                frameworkService: $this->frameworkService
            ));
            $output = $handler->handle(
                createUserInput: $createUserInput,
                accountInput: $accountInput
            );

            $this->frameworkService->transactionManager()->commit();
        } catch (OutputErrorException $outputErrorException) {
            $this->frameworkService->transactionManager()->rollBack();
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
            status: ResponseStatus::CREATED->value
        );
    }
}
