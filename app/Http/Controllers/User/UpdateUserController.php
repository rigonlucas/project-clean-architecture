<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\UpdateUserUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        $input = new UpdateUserInput(
            id: $id,
            name: 'name 2',
            email: 'email@2.com.br',
            password: Hash::make('password'),
            birthday: now()->subYears(18)
        );

        $useCase = new UpdateUserUseCase(
            new AppAdapter(),
            new UserRepository(),
            new UserCommand()
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
