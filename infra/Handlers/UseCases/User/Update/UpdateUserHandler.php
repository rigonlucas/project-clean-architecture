<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Core\Services\Framework\FrameworkContract;

class UpdateUserHandler
{
    public function __construct(
        private readonly UserCommandInterface $userCommand,
        private readonly UserRepositoryInterface $userRepository,
        private readonly FrameworkContract $frameworkService
    ) {
    }


    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            $this->frameworkService,
            $this->userRepository,
            $this->userCommand
        );
        return new UpdateUserOutput($useCase->execute($input));
    }
}
