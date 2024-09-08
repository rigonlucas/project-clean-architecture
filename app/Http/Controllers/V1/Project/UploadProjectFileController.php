<?php

namespace App\Http\Controllers\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Project\UploadProjectFileRequest;
use Core\Application\File\Shared\Gateways\FileCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Shared\Inputs\FiletInput;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\Enum\File\FileTypeEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Infra\Handlers\UseCases\Project\Upload\UploadProjectFileHandler;
use Ramsey\Uuid\Uuid;

class UploadProjectFileController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly FileCommandInterface $fileCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function __invoke(UploadProjectFileRequest $request, string $uuid)
    {
        $uploadedFile = $request->file('file');
        $input = new FiletInput(
            name: $uploadedFile->name,
            type: FileTypeEnum::DOCUMENT,
            size: new BytesValueObject($uploadedFile->getSize()),
            extension: FileExtensionsEnum::from($uploadedFile->getClientOriginalExtension()),
            contextFile: FileContextEnum::PROJECT,
            uuid: Uuid::fromString($uuid)
        );
        try {
            $this->framework->transactionManager()->beginTransaction();

            $projectFileHandler = new UploadProjectFileHandler(
                framework: $this->framework,
                fileProjectCommand: $this->fileCommand,
                projectMapper: $this->projectMapper
            );
            $fileEntity = $projectFileHandler->handle($input, $uploadedFile);

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
                    'uuid' => $fileEntity->getUuid()->toString(),
                    'project_uuid' => $uuid,
                    'file_name' => $fileEntity->getUlidFileName() . '.' . $fileEntity->getExtension()->value,
                ]
            ],
            status: ResponseStatus::OK->value
        );
    }
}
