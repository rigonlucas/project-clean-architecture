<?php

namespace App\Http\Controllers\V1\Project;

use App\Http\Controllers\Controller;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Http\Request;
use Infra\Handlers\UseCases\Project\Delete\DeleteProjectHandler;
use Ramsey\Uuid\Uuid;

class SoftDeleteProjectController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly ProjectCommandInterface $projectCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function __invoke(Request $request, string $uuid)
    {
        try {
            $this->framework->transactionManager()->beginTransaction();

            $handler = new DeleteProjectHandler(
                framework: $this->framework,
                projectCommand: $this->projectCommand,
                projectMapper: $this->projectMapper
            );
            $aggregate = $handler->execute(Uuid::fromString($uuid), $this->framework->auth()->user());

            $this->framework->transactionManager()->commit();
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
                    'project_uuid' => $aggregate->projectEntity->getUuid(),
                    'project_signature' => $aggregate->ulidDeletion,
                    'deleted_relations' => $aggregate->tables
                ]
            ],
            status: ResponseStatus::OK->value
        );
    }
}
