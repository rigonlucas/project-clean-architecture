<?php

namespace Core\Domain\Aggregates\Project;

use Core\Domain\Entities\Project\Root\ProjectEntity;

readonly class ProjectDeletionAggregate
{
    public function __construct(
        public ProjectEntity $projectEntity,
        public string $uuidProject,
        public string $ulidDeletion,
        public array $tables
    ) {
    }
}
