<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\User\Show\ShowUserUseCase;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserDetaisPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Infra\Database\User\Repository\UserRepository;
use Ramsey\Uuid\Uuid;

class ShowUserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly FrameworkContract $frameworkService
    ) {
    }

    public function __invoke(string $uuid)
    {
        try {
            $useCase = new ShowUserUseCase(
                userRepository: $this->userRepository,
                accountRepository: $this->accountRepository
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
            status: ResponseStatusCodeEnum::OK->value
        );
    }
}
