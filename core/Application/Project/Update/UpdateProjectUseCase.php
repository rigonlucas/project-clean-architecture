<?php

namespace Core\Application\Project\Update;

use Core\Application\Project\Commons\Exceptions\ProjectAlreadyExistsException;
use Core\Application\Project\Commons\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Commons\Validations\HasProjectWithSameNameValidation;
use Core\Application\Project\Update\inputs\UpdateProjectInput;
use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\StatusValidationFactory;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\Dates\DateMustBeBeforeOtherException;
use Core\Support\Exceptions\Dates\DateMustBeInCurrentDayException;
use Core\Support\Exceptions\Dates\DateRequiredException;
use Core\Support\Exceptions\Dates\DatesMustBeDifferntsException;
use Core\Support\Http\ResponseStatus;

readonly class UpdateProjectUseCase
{
    private HasProjectWithSameNameValidation $nameValidation;

    public function __construct(
        private ProjectCommandInterface $projectCommand,
        private ProjectMapperInterface $projectMapper
    ) {
        $this->nameValidation = new HasProjectWithSameNameValidation($this->projectMapper);
    }

    /**
     * @param UpdateProjectInput $createProjectInput
     * @param UserEntity $authUser
     * @return ProjectEntity
     * @throws ProjectAlreadyExistsException
     * @throws ProjectNotFoundException
     * @throws ForbidenException
     * @throws DateMustBeBeforeOtherException
     * @throws DateMustBeInCurrentDayException
     * @throws DateRequiredException
     * @throws DatesMustBeDifferntsException
     */
    public function execute(UpdateProjectInput $createProjectInput, UserEntity $authUser): ProjectEntity
    {
        $recordedProjectEntity = $this->projectMapper->findByUuid($createProjectInput->uuid, $authUser);
        if (!$recordedProjectEntity) {
            throw new ProjectNotFoundException(
                'Project not found',
                ResponseStatus::NOT_FOUND->value
            );
        }

        $hasProjectWithSameName = $this->nameValidation->validate($createProjectInput->name, $authUser);
        if ($hasProjectWithSameName) {
            throw new ProjectAlreadyExistsException(
                'Project already exists with name ([' . $createProjectInput->name . ')',
                ResponseStatus::UNPROCESSABLE_ENTITY->value
            );
        }

        $recordedProjectEntity->setName($createProjectInput->name);
        $recordedProjectEntity->setDescription($createProjectInput->description);
        $recordedProjectEntity->setStartAt($createProjectInput->startAt);
        $recordedProjectEntity->setFinishAt($createProjectInput->finishAt);
        $recordedProjectEntity->setStatus($createProjectInput->status);

        $recordedProjectEntity->canChangeProject();
        $recordedProjectEntity->datesValidation();

        StatusValidationFactory::make($recordedProjectEntity)->validateWithThrowException();


        return $this->projectCommand->update($recordedProjectEntity);
    }
}
