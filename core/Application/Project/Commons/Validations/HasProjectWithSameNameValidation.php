<?php

namespace Core\Application\Project\Commons\Validations;

use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
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
