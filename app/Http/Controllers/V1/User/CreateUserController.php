<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateUserRequest;
use Carbon\Carbon;
use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountMapperInterface;
use Core\Application\Account\Create\Inputs\AccountInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\User\Create\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __construct(
        private readonly UserCommandInterface $userCommand,
        private readonly UserMapperInterface $userMapper,
        private readonly AccountCommandInterface $accountCommand,
        private readonly AccountMapperInterface $accountMapper,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    /**
     * @throws InvalidEmailException
     */
    public function __invoke(CreateUserRequest $request)
    {
        $createUserInput = new CreateUserInput(
            name: $request->name,
            email: new EmailValueObject(email: $request->email, autoValidete: false),
            password: $request->password,
            birthday: Carbon::createFromFormat(format: 'Y-m-d', time: $request->birthday)
        );

        $accountInput = new AccountInput(
            accessCode: $request->account_access_code
        );
        try {
            $this->frameworkService->transactionManager()->beginTransaction();

            $handler = (new CreateUserHandler(
                userCommand: $this->userCommand,
                userMapper: $this->userMapper,
                accountCommand: $this->accountCommand,
                accountMapper: $this->accountMapper,
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
