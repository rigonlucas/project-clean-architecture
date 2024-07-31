<?php

namespace Tests\Unit\User\Entity;

use Core\Adapters\App\AppAdapter;
use Core\Modules\User\Commons\Entities\UserEntity;
use Tests\TestCase;

/**
 * @group UserEntity
 */
class UserEntityTest extends TestCase
{
    public function test_deve_retornar_que_o_usuario_nao_tem_idade_legal(): void
    {
        // Arrange
        $userEntity = UserEntity::create(
            name: 'John Doe',
            email: '',
            password: 'password',
            uuid: AppAdapter::getInstance()->uuid7Generate(),
            birthday: now()->subYears(17)
        );
        $this->assertTrue($userEntity->hasNoLegalAge());
    }

    public function test_deve_retornar_nome_do_usuario(): void
    {
        // Arrange
        $userEntity = UserEntity::create(
            name: 'John Doe',
            email: 'john@email.com',
            password: 'password',
            uuid: AppAdapter::getInstance()->uuid7Generate(),
            birthday: now()->subYears(18)
        );
        // Act
        $this->assertFalse($userEntity->hasNoLegalAge());
        $name = $userEntity->getName();

        // Assert
        $this->assertEquals('John Doe', $name);
    }
}
