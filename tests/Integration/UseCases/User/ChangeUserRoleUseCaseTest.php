<?php

namespace Tests\Integration\UseCases\User;

use App\Models\User;
use Core\Application\User\ChangeRole\ChangeUserRoleUseCase;
use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserMapperInterface;
use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Permissions\UserRoles;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group change_user_role_use_case
 */
class ChangeUserRoleUseCaseTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private ChangeUserRoleUseCase $useCase;

    public function test_success_case_for_change_role_from_any_user_when_authenticated_user_is_admin(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER,
                'account_id' => $userAuth->account_id
            ]
        );
        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $userToChange->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);

        $this->assertDatabaseHas('users', [
            'uuid' => $userToChange->uuid,
            'role' => UserRoles::ADMIN
        ]);
    }

    public function test_success_case_for_change_role_when_is_the_same_already_defined(): void
    {
        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::ADMIN,
                'account_id' => $userAuth->account_id
            ]
        );
        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $userToChange->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);

        $this->assertDatabaseHas('users', [
            'uuid' => $userToChange->uuid,
            'role' => UserRoles::ADMIN
        ]);
    }

    public function test_fail_case_for_change_role_from_any_user_when_authenticated_user_as_viewer_admin(): void
    {
        $this->expectException(ForbidenException::class);

        $userAuth = User::factory()->create([
            'role' => UserRoles::VIEWER
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER,
                'account_id' => $userAuth->account_id
            ]
        );
        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $userToChange->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);
    }

    public function test_fail_case_for_change_role_from_any_user_when_authenticated_user_as_editor_admin(): void
    {
        $this->expectException(ForbidenException::class);

        $userAuth = User::factory()->create([
            'role' => UserRoles::EDITOR
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::EDITOR,
                'account_id' => $userAuth->account_id
            ]
        );
        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $userToChange->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);
    }

    public function test_fail_when_user_not_found(): void
    {
        $this->expectException(UserNotFountException::class);

        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $this->faker->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);
    }

    public function test_tail_if_users_arent_from_same_account(): void
    {
        $this->expectException(ForbidenException::class);

        $userAuth = User::factory()->create([
            'role' => UserRoles::ADMIN
        ]);
        Sanctum::actingAs(
            $userAuth,
            ['*']
        );

        $userToChange = User::factory()->create(
            [
                'role' => UserRoles::VIEWER
            ]
        );
        $input = new ChangeUserRoleInput(
            authenticatedUser: UserEntity::forIdentify(
                id: $userAuth->id,
                uuid: Uuid::fromString($userAuth->uuid),
                role: $userAuth->role,
                accountId: $userAuth->account_id
            ),
            userUuid: $userToChange->uuid,
            role: UserRoles::ADMIN
        );
        $this->useCase->execute($input);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new ChangeUserRoleUseCase(
            $this->app->make(UserCommandInterface::class),
            $this->app->make(UserMapperInterface::class)
        );
    }
}
