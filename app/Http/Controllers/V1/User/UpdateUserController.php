<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UpdateUserRequest;
use Carbon\Carbon;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Infra\Handlers\UseCases\User\UpdateUserHandler;
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

        $handler = (new UpdateUserHandler())->handle($input);
        $output = $handler->getOutput();
        $presenter = $output->getPresenter();

        return response()->json(
            $presenter->toArray(),
            $output->status->statusCode
        );
    }
}
