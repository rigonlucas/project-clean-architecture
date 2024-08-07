<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Illuminate\Http\Request;
use Infra\Handlers\UseCases\User\UpdateUserHandler;
use Ramsey\Uuid\Uuid;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, string $uuid)
    {
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($uuid),
            name: 'name 2',
            email: 'email32@email.com',
            password: 'password',
            birthday: now()->subYears(17)
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
