<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Update\Inputs\UpdateUserInput;
use Core\Modules\User\Update\Output\UpdateUserOutput;
use Core\Modules\User\Update\UpdateUserUseCase;
use Core\Tools\Http\ResponseStatusCodeEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Infra\Persistence\User\Command\UserCommand;
use Infra\Persistence\User\Repository\UserRepository;
use Tests\TestCase;

class UpdateUserUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_must_create_a_user(): void
    {
        // Arrange
        $userFactory = User::factory()->create([
            'birthday' => now()->subYears(19)
        ]);

        $useCase = new UpdateUserUseCase(
            AppAdapter::getInstance(),
            new UserRepository(),
            new UserCommand()
        );
        $input = new UpdateUserInput(
            uuid: $userFactory->id,
            name: 'name 2',
            email: 'email3@email.com',
            password: 'password',
            birthday: now()->subYears(18)
        );

        // Act
        $useCase->execute($input);
        /** @var UpdateUserOutput $output */
        $output = $useCase->getOutput();

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $output->userEntity->getId(),
            'name' => $output->userEntity->getName(),
            'email' => $output->userEntity->getEmail(),
            'password' => $output->userEntity->getPassword(),
            'birthday' => $output->userEntity->getBirthday()
        ]);
        $this->assertEquals(ResponseStatusCodeEnum::OK->value, $output->status->statusCode);
        $this->assertNotEquals($input->password, $output->userEntity->getPassword());
    }
}
