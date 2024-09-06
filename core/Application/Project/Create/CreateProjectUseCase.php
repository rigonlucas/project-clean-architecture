<?php

namespace Core\Application\Project\Create;

use Core\Application\Project\Create\inputs\CreateProjectInput;
use Core\Application\Project\Shared\Exceptions\ProjectAlreadyExistsException;
use Core\Application\Project\Shared\Exceptions\ProjectStatusUnableException;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Project\Shared\Validations\HasProjectWithSameNameValidation;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Http\ResponseStatus;

readonly class CreateProjectUseCase
{
    private HasProjectWithSameNameValidation $nameValidation;

    public function __construct(
        private FrameworkContract $framework,
        private ProjectCommandInterface $projectCommand,
        private ProjectMapperInterface $projectMapper
    ) {
        $this->nameValidation = new HasProjectWithSameNameValidation($this->projectMapper);
    }

    /**
     * @throws DateMustBeBeforeOtherException
     * @throws ProjectAlreadyExistsException
     * @throws ForbidenException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     * @throws ProjectStatusUnableException
     */
    public function execute(CreateProjectInput $createProjectInput, UserEntity $authUser): ProjectEntity
    {
        $hasProjectWithSameName = $this->nameValidation->validate($createProjectInput->name, $authUser);
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

        $projectEntity->canChangeProject();
        $projectEntity->canCreate();
        $projectEntity->datesValidation();

        return $this->projectCommand->create($projectEntity);
    }
}
