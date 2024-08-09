<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UpdateUserRequest;
use Carbon\Carbon;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Handlers\UseCases\User\UpdateUserHandler;
use Core\Presentation\Errors\ErrorPresenter;
use Core\Presentation\User\UpdateUserPresenter;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Ramsey\Uuid\Uuid;

class UpdateUserController extends Controller
{
    public function __invoke(UpdateUserRequest $request)
    {
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($request->uuid),
            name: $request->name,
            email: $request->email,
            password: $request->password,
            birthday: Carbon::createFromFormat('Y-m-d', $request->birthday)
        );

        try {
            $output = (new UpdateUserHandler())->handle($input);
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
            (new UpdateUserPresenter($output->userEntity))->withDataAttribute()->toArray(),
            ResponseStatusCodeEnum::OK->value
        );
    }
}
