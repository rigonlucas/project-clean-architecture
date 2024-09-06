<?php

namespace Infra\Handlers\UseCases\Project\Upload;

use Core\Application\File\Shared\Gateways\FileCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Project\Upload\ProjectUploadFileUseCase;
use Core\Application\Shared\Inputs\FiletInput;
use Core\Domain\Entities\File\Root\FileEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\ErrorOnUploadToStorageException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadProjectFileHandler
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly FileCommandInterface $fileProjectCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function handle(FiletInput $projecFiletInput, UploadedFile $file): FileEntity
    {
        $fileEntity = $this->applyRegisterFile($projecFiletInput);
        $this->pushFile($fileEntity, $file);
        $this->checkIfFileWasSaved($fileEntity);

        return $fileEntity;
    }

    private function applyRegisterFile(FiletInput $projecFiletInput): FileEntity
    {
        $projectUploadFileUseCase = new ProjectUploadFileUseCase(
            framework: $this->framework,
            fileCommand: $this->fileProjectCommand,
            projectMapper: $this->projectMapper
        );
        return $projectUploadFileUseCase->execute($projecFiletInput, $this->framework->auth()->user());
    }

    private function pushFile(FileEntity $fileEntity, UploadedFile $file): void
    {
        $directory = dirname($fileEntity->getPath());
        $baseName = basename($fileEntity->getPath());

        $returnFromStorage = Storage::disk(config('filesystems.default'))
            ->putFileAs(
                $directory,
                $file,
                $baseName
            );
        if (!$returnFromStorage) {
            throw new ErrorOnUploadToStorageException(
                message: 'Error to save file in the storage, we are working to solve this problem',
                code: ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
    }

    private function checkIfFileWasSaved(FileEntity $fileEntity): void
    {
        $fileExists = Storage::disk(config('filesystems.default'))->exists($fileEntity->getPath());
        if (!$fileExists) {
            throw new ErrorOnUploadToStorageException(
                message: 'File was not saved in the storage, we are working to solve this problem',
                code: ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
        $fileEntity->confirmUpload();
        $this->fileProjectCommand->confirmUploadFile($fileEntity);
    }
}
