<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Illuminate\Http\Request;
use Infra\Handlers\UseCases\User\CreateUserHandler;

class CreateUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $createUserInput = new CreateUserInput(
            name: 'name 4231231232asdasd2',
            email: 'email32@email.com',
            password: 'password',
            birthday: now()->subYears(18)
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
