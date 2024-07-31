<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Infra\Handlers\UseCases\User\UpdateUserHandler;
use Ramsey\Uuid\Uuid;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, string $uuid)
    {
        $input = new UpdateUserInput(
            uuid: Uuid::fromString($uuid),
            name: 'name 2',
            email: 'email@2.com.br',
            password: Hash::make('password'),
            birthday: now()->subYears(18)
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
