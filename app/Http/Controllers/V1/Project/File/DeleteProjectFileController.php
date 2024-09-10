<?php

namespace App\Http\Controllers\V1\Project\File;

use App\Http\Controllers\Controller;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectFileMapperInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Http\Request;
use Infra\Handlers\UseCases\Project\File\Delete\DeleteProjectFileHandler;

class DeleteProjectFileController extends Controller
{
    public function __construct(
        private FrameworkContract $framework,
        private ProjectMapperInterface $projectMapper,
        private ProjectFileMapperInterface $projectFileMapper,
        private ProjectCommandInterface $projectCommand
    ) {
    }

    public function __invoke(Request $request, string $projectUuid, string $fileUuid)
    {
        try {
            $this->framework->transactionManager()->beginTransaction();

            $handler = new DeleteProjectFileHandler(
                projectMapper: $this->projectMapper,
                projectFileMapper: $this->projectFileMapper,
                projectCommand: $this->projectCommand
            );
            $handler->handler(
                fileUuid: $this->framework->uuid()->uuidFromString($fileUuid),
                projectUuid: $this->framework->uuid()->uuidFromString($projectUuid),
                userEntity: $this->framework->auth()->user()
            );
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
            status: ResponseStatus::NO_CONTENT->value
        );
    }
}
