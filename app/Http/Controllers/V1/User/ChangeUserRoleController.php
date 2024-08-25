<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\ChangeUserRoleRequest;
use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\User\ChangeRole\ChangeRoleUserHandler;

class ChangeUserRoleController extends Controller
{
    public function __construct(
        private readonly UserCommandInterface $userCommand,
        private readonly UserMapperInterface $userMapper,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    public function __invoke(ChangeUserRoleRequest $request, string $userUuid)
    {
        $userAutenticated = $this->frameworkService->auth()->user();
        $changeRoleInput = new ChangeUserRoleInput(
            authenticatedUser: $userAutenticated,
            userUuid: $userUuid,
            role: $request->role
        );
        try {
            $this->frameworkService->transactionManager()->beginTransaction();

            $handler = new ChangeRoleUserHandler(
                userCommand: $this->userCommand,
                userMapper: $this->userMapper
            );
            $handler->handle($changeRoleInput);

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
            status: ResponseStatus::NO_CONTENT->value
        );
    }
}
