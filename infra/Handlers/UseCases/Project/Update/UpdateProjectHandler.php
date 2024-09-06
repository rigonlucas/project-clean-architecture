<?php

namespace Infra\Handlers\UseCases\Project\Update;

use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Project\Update\inputs\UpdateProjectInput;
use Core\Application\Project\Update\UpdateProjectUseCase;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;

class UpdateProjectHandler
{
    public function __construct(
        private UserEntity $userAuth,
        private ProjectCommandInterface $projectCommand,
        private ProjectMapperInterface $projectMapper
    ) {
    }

    public function handle(UpdateProjectInput $input): ProjectEntity
    {
        $createProjectUseCase = new UpdateProjectUseCase(
            projectCommand: $this->projectCommand,
            projectMapper: $this->projectMapper
        );
        return $createProjectUseCase->execute($input, $this->userAuth);
    }
}
