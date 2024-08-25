<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\OutputErrorException;

readonly class UpdateUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserMapperInterface $userMapper,
        private FrameworkContract $frameworkService
    ) {
    }


    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            $this->frameworkService,
            $this->userMapper,
            $this->userCommand
        );
        $userEntity = $useCase->execute($input);

        return new UpdateUserOutput($userEntity);
    }
}
