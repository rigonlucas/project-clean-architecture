<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\ChangeUserRoleRequest;
use Core\Application\User\ChangeRole\Inputs\ChangeRoleInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Infra\Handlers\UseCases\User\ChangeRole\ChangeRoleUserHandler;

class ChangeUserRoleController extends Controller
{
    public function __construct(
        private readonly UserCommandInterface $userCommandInterface,
        private readonly UserRepositoryInterface $userRepositoryInterface,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    public function __invoke(ChangeUserRoleRequest $request, string $userUuid)
    {
        $userAutenticated = $this->frameworkService->auth()->user();
        $changeRoleInput = new ChangeRoleInput(
            authenticatedUser: $userAutenticated,
            userUuid: $userUuid,
            role: $request->role
        );
        try {
            $this->frameworkService->transactionManager()->beginTransaction();

            $handler = new ChangeRoleUserHandler(
                userCommand: $this->userCommandInterface,
                userRepository: $this->userRepositoryInterface
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
            status: ResponseStatusCodeEnum::NO_CONTENT->value
        );
    }
}
