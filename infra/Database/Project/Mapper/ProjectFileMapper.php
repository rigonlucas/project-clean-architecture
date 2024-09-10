<?php

namespace Infra\Database\Project\Mapper;

use App\Models\ProjectFile;
use Core\Application\Project\Shared\Gateways\ProjectFileMapperInterface;
use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;

class ProjectFileMapper implements ProjectFileMapperInterface
{

    public function findByUuid(UuidInterface $uuid, UuidInterface $projectUuid): ?ProjectFileEntity
    {
        $projectFileModel = ProjectFile::query()
            ->select('uuid', 'file_path', 'project_uuid')
            ->where('uuid', '=', $uuid)
            ->where('project_uuid', '=', $projectUuid)
            ->toBase()
            ->first();
        if (!$projectFileModel) {
            return null;
        }

        return ProjectFileEntity::forDelete(
            Uuid::fromString($projectFileModel->uuid),
            Uuid::fromString($projectFileModel->project_uuid),
            $projectFileModel->file_path
        );
    }
}
