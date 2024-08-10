<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateUserRequest;
use Carbon\Carbon;
use Core\Application\User\Create\Inputs\CreateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Presentation\Http\User\UserPresenter;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Infra\Handlers\UseCases\User\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __invoke(CreateUserRequest $request)
    {
        $createUserInput = new CreateUserInput(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            birthday: Carbon::createFromFormat('Y-m-d', $request->birthday)
        );
        try {
            $output = (new CreateUserHandler())->handle($createUserInput);
        } catch (OutputErrorException $outputErrorException) {
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
            status: ResponseStatusCodeEnum::CREATED->value
        );
    }
}
