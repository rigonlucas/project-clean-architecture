<?php

namespace App\Http\Controllers\V1\Project\File;

use App\Http\Controllers\Controller;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Http\Request;

class DeleteProjectFileController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $framework
    ) {
    }

    public function __invoke(Request $request, string $uuid)
    {
        try {
            $this->framework->transactionManager()->beginTransaction();
        } catch (OutputErrorException $outputErrorException) {
            $this->framework->transactionManager()->rollBack();
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(
            data: [
                'data' => [

                ]
            ],
            status: ResponseStatus::OK->value
        );
    }
}
