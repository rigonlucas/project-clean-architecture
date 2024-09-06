<?php

namespace Core\Application\Project\Shared\Validations;

use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Shared\User\Root\UserEntity;

readonly class HasProjectWithSameNameValidation
{
    public function __construct(
        private ProjectMapperInterface $projectMapper
    ) {
    }

    public function validate(string $name, UserEntity $authUser): bool
    {
        return $this->projectMapper->existsByName($name, $authUser->getAccount());
    }
}
