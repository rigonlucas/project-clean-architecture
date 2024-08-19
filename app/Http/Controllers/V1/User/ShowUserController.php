<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Application\User\Show\ShowUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Exception;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\FrameworkService;

class ShowUserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function __invoke(string $uuid)
    {
        try {
            $useCase = new ShowUserUseCase(
                framework: FrameworkService::getInstance(),
                userRepository: $this->userRepository
            );
            $userEntity = $useCase->execute(uuid: $uuid);
        } catch (OutputErrorException $outputErrorException) {
            return response()->json(
                data: (new ErrorPresenter(
                    message: 'Contém erros de validação',
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        } catch (Exception $exception) {
            dd($exception);
        }

        return response()->json(
            data: (new UserPresenter($userEntity))->withDataAttribute()->toArray(),
            status: ResponseStatusCodeEnum::OK->value
        );
    }
}
