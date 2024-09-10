<?php

namespace Core\Domain\Entities\Project\File;

use Ramsey\Uuid\UuidInterface;

class ProjectFileEntity
{
    use HasProjectFileBuilder;

    private UuidInterface $uuid;
    private string $path;
    private UuidInterface $projectUuid;

    private function __construct()
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getProjectUuid(): UuidInterface
    {
        return $this->projectUuid;
    }

    public function setProjectUuid(UuidInterface $projectUuid): void
    {
        $this->projectUuid = $projectUuid;
    }


}
