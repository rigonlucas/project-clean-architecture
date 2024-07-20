<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Core\Generics\Outputs\OutputError;
use Core\Modules\User\Create\CreateUserUseCase;
use Core\Modules\User\Create\Inputs\CreateUserInput;
use Core\Modules\User\Create\outputs\CreateUserOutput;
use Illuminate\Http\Request;
use Infra\Persistence\User\Command\UserCommand;

class CreateUser extends Controller
{
    public function __invoke(Request $request)
    {
        $input = new CreateUserInput(
            name: 'name 4',
            email: 'email 4',
            password: 'password',
            age: 17
        );

        $useCase = new CreateUserUseCase(
            new UserCommand()
        );
        $useCase->execute($input);
        $output = $useCase->getOutput();

        /** @var CreateUserOutput $output */
        if ($output->status->statusCode === 201) {
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
