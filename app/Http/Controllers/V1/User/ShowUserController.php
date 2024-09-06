<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Application\Account\Shared\Gateways\AccountMapperInterface;
use Core\Application\User\Show\ShowUserUseCase;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserDetaisPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Database\User\Mapper\UserMapper;
use Ramsey\Uuid\Uuid;

class ShowUserController extends Controller
{
    public function __construct(
        private readonly UserMapper $userMapper,
        private readonly AccountMapperInterface $accountMapper,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    public function __invoke(string $uuid)
    {
        try {
            $useCase = new ShowUserUseCase(
                userMapper: $this->userMapper,
                accountMapper: $this->accountMapper
            );
            $userEntity = $useCase->execute(
                uuid: Uuid::fromString($uuid),
                userAuthenticaded: $this->frameworkService->auth()->user()
            );
        } catch (OutputErrorException $outputErrorException) {
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: (new UserDetaisPresenter($userEntity))->withDataAttribute()->toArray(),
            status: ResponseStatus::OK->value
        );
    }
}
