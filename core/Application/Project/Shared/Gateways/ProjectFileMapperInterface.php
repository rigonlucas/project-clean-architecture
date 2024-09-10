<?php

namespace Core\Application\Project\Shared\Gateways;

use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Ramsey\Uuid\UuidInterface;

interface ProjectFileMapperInterface
{
    public function findByUuid(UuidInterface $uuid, UuidInterface $projectUuid): ?ProjectFileEntity;
}