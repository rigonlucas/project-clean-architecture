<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Create\CreateUserUseCase;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Illuminate\Http\Request;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class CreateUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = new CreateUserInput(
            name: 'name 4231231232asdasd2',
            email: 'email 422312asdas3223',
            password: 'password',
            birthday: now()->subYears(18)
        );

        $useCase = new CreateUserUseCase(
            new AppAdapter(),
            new UserCommand(),
            new UserRepository()
        );
        $useCase->execute($input);
        $output = $useCase->getOutput();
        $presenter = $output->getPresenter();

        return response()->json(
            $presenter->toArray(),
            $output->status->statusCode
        );
    }
}
