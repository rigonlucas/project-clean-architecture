<?php

namespace Infra\Handlers\UseCases\Project\Delete;

use Core\Application\Project\Delete\DeleteProjectUseCase;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Aggregates\Project\ProjectDeletionAggregate;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Ramsey\Uuid\UuidInterface;

class DeleteProjectHandler
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly ProjectCommandInterface $projectCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function execute(UuidInterface $uuid, UserEntity $userAuth): ProjectDeletionAggregate
    {
        $useCase = new DeleteProjectUseCase(
            $this->framework,
            $this->projectCommand,
            $this->projectMapper
        );

        return $useCase->execute($uuid, $userAuth);
    }
}
