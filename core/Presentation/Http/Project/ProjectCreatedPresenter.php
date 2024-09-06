<?php

namespace Core\Presentation\Http\Project;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Support\Presentation\PresentationBase;

class ProjectCreatedPresenter extends PresentationBase
{
    public function __construct(ProjectEntity $userEntity)
    {
        $this->data = [
            'uuid' => $userEntity->getUuid()->toString()
        ];
    }
}
