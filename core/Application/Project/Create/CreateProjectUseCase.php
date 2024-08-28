<?php

namespace Core\Application\Project\Create;

use Core\Application\Project\Commons\Exceptions\ProjectAlreadyExistsException;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Create\inputs\CreateProjectInput;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Http\ResponseStatus;

readonly class CreateProjectUseCase
{
    public function __construct(
        private FrameworkContract $framework,
        private ProjectCommandInterface $projectCommand,
        private ProjectMapperInterface $projectMapper
    ) {
    }

    /**
     * @throws DateMustBeBeforeOtherException
     * @throws ProjectAlreadyExistsException
     * @throws ForbidenException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     */
    public function execute(CreateProjectInput $createProjectInput, UserEntity $authUser): ProjectEntity
    {
        $hasProjectWithSameName = $this->projectMapper->existsByName(
            $createProjectInput->name,
            $authUser->getAccount()
        );
        if ($hasProjectWithSameName) {
            throw new ProjectAlreadyExistsException(
                'Project already exists with name ([' . $createProjectInput->name . ')',
                ResponseStatus::UNPROCESSABLE_ENTITY->value
            );
        }
        $projectEntity = ProjectEntity::forCreate(
            name: $createProjectInput->name,
            description: $createProjectInput->description,
            user: $authUser,
            account: $authUser->getAccount(),
            uuid: $this->framework->uuid()->uuid7Generate(),
            status: $createProjectInput->status,
            startAt: $createProjectInput->startAt,
            finishAt: $createProjectInput->finishAt
        );

        return $this->projectCommand->create($projectEntity);
    }
}
