<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Application\User\Update\Inputs\UpdateUserInput;
use Core\Application\User\Update\UpdateUserUseCase;
use Core\Generics\Exceptions\OutputErrorException;
use Infra\Services\Framework\FrameworkService;

class UpdateUserHandler
{
    public function __construct(
        private readonly UserCommandInterface $userCommandInterface,
        private readonly UserRepositoryInterface $userRepositoryInterface
    ) {
    }


    /**
     * @throws OutputErrorException
     */
    public function handle(UpdateUserInput $input): UpdateUserOutput
    {
        $useCase = new UpdateUserUseCase(
            FrameworkService::getInstance(),
            $this->userRepositoryInterface,
            $this->userCommandInterface
        );
        return new UpdateUserOutput($useCase->execute($input));
    }
}
