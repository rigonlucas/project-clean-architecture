<?php

namespace Infra\Handlers\UseCases\Project\Create;

use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Create\CreateProjectUseCase;
use Core\Application\Project\Create\inputs\CreateProjectInput;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;

readonly class CreateProjectHandler
{
    public function __construct(
        private UserEntity $userAuth,
        private FrameworkContract $framework,
        private ProjectCommandInterface $projectCommand,
        private ProjectMapperInterface $projectMapper
    ) {
    }

    public function handle(CreateProjectInput $input): ProjectEntity
    {
        $createProjectUseCase = new CreateProjectUseCase(
            framework: $this->framework,
            projectCommand: $this->projectCommand,
            projectMapper: $this->projectMapper
        );
        return $createProjectUseCase->execute($input, $this->userAuth);
    }
}
