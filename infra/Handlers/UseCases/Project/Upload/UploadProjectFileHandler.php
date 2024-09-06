<?php

namespace Infra\Handlers\UseCases\Project\Upload;

use Core\Application\Common\Inputs\FiletInput;
use Core\Application\File\Gateways\FileCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Upload\ProjectUploadFileUseCase;
use Core\Domain\Entities\File\Root\FileEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Http\ResponseStatus;
use Exception;
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

        Storage::disk(config('filesystems.default'))
            ->putFileAs(
                $directory,
                $file,
                $baseName
            );
    }

    private function checkIfFileWasSaved(FileEntity $fileEntity): void
    {
        $fileExists = Storage::disk(config('filesystems.default'))->exists($fileEntity->getPath());
        if (!$fileExists) {
            throw new Exception(
                message: 'File not found in storage',
                code: ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
        $fileEntity->confirmUpload();
        $this->fileProjectCommand->confirmUploadFile($fileEntity);
    }
}
