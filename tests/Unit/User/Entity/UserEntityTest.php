<?php

namespace Tests\Unit\User\Entity;

use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Tests\TestCase;

class UserEntityTest extends TestCase
{
    public function test_deve_lancar_exception_de_idade_invalida(): void
    {
        $this->expectException(InvalidAgeException::class);
        // Arrange
        new UserEntity(
            name: 'John Doe',
            email: '',
            password: '',
            age: 17
        );
    }

    public function test_deve_retornar_nome_do_usuario(): void
    {
        // Arrange
        $user = new UserEntity(
            name: 'John Doe',
            email: 'john@email.com',
            password: '',
            age: 18
        );
        // Act
        $name = $user->getName();

        // Assert
        $this->assertEquals('John Doe', $name);
    }
}
