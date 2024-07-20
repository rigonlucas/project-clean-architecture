<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Generics\Outputs\OutputError;
use Core\Modules\User\Create\outputs\CreateUserOutput;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\UpdateUserUseCase;
use Illuminate\Http\Request;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        $input = new UpdateUserInput(
            id: $id,
            name: 'name 2',
            email: 'email 2',
            password: 'password 2',
            age: 18
        );

        $useCase = new UpdateUserUseCase(
            new UserRepository(),
            new UserCommand()
        );
        $useCase->execute($input);
        $output = $useCase->getOutput();

        /** @var CreateUserOutput $output */
        if ($output->status->statusCode === 200) {
            return response()->json(
                [
                    'message' => $output->status->message,
                    'data' => $output->userEntity->getName()
                ],
                $output->status->statusCode
            );
        }

        if ($output->status->statusCode === 500) {
            return response()->json(
                [
                    'message' => $output->status->message,
                    'data' => $output->status->message
                ],
                $output->status->statusCode
            );
        }

        /** @var OutputError $output */
        return response()->json(
            [
                'message' => $output->status->message,
                'data' => $output->message
            ],
            $output->status->statusCode
        );
    }
}
