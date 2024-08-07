<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateUserRequest;
use Carbon\Carbon;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Infra\Handlers\UseCases\User\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __invoke(CreateUserRequest $request)
    {
        $createUserInput = new CreateUserInput(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            birthday: Carbon::createFromFormat('d/m/Y', $request->birthday)
        );

        $handler = (new CreateUserHandler())->handle($createUserInput);
        $output = $handler->getOutput();
        $presenter = $output->getPresenter();

        return response()->json(
            $presenter->toArray(),
            $output->status->statusCode
        );
    }
}
