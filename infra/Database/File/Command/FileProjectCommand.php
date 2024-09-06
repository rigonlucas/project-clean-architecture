<?php

namespace Infra\Database\File\Command;

use App\Models\ProjectFile;
use Core\Application\File\Shared\Gateways\FileCommandInterface;
use Core\Domain\Entities\File\Root\FileEntity;
use Ramsey\Uuid\UuidInterface;

class FileProjectCommand implements FileCommandInterface
{
    public function create(FileEntity $projectFileEntity, UuidInterface $referenciaUuid): FileEntity
    {
        $projectFile = new ProjectFile();
        $projectFile->uuid = $projectFileEntity->getUuid();
        $projectFile->file_name = $projectFileEntity->getName();
        $projectFile->file_path = $projectFileEntity->getPath();
        $projectFile->file_type = $projectFileEntity->getType()->value;
        $projectFile->file_size = $projectFileEntity->getSize()->getBytes();
        $projectFile->file_extension = $projectFileEntity->getExtension();
        $projectFile->project_uuid = $referenciaUuid->toString();
        $projectFile->created_by_user_uuid = $projectFileEntity->getUserEntity()->getUuid();
        $projectFile->account_uuid = $projectFileEntity->getUserEntity()->getAccount()->getUuid();
        $projectFile->context = $projectFileEntity->getContext()->value;
        $projectFile->status = $projectFileEntity->getStatus()->value;
        $projectFile->save();

        return $projectFileEntity;
    }

    public function confirmUploadFile(FileEntity $fileEntity): void
    {
        ProjectFile::query()
            ->where('uuid', '=', $fileEntity->getUuid())
            ->update([
                'status' => $fileEntity->getStatus()->value
            ]);
    }
}
