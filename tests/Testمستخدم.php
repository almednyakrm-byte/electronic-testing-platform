<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

class TestUser extends TestCase
{
    private $pdoMock;
    private $userController;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->userController = new UserController($this->pdoMock);
    }

    public function testGetAllUsers()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM users')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $response = new Response();

        $result = $this->userController->getAllUsers($request, $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function testCreateUser()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')
            ->willReturn(['name' => 'John Doe', 'email' => 'john@example.com']);

        $response = new Response();

        $result = $this->userController->createUser($request, $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function testUpdateUser()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE users SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with('route', 'user')
            ->willReturn(['id' => 1]);
        $request->method('getParsedBody')
            ->willReturn(['name' => 'John Doe', 'email' => 'john@example.com']);

        $response = new Response();

        $result = $this->userController->updateUser($request, $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function testDeleteUser()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM users WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with('route', 'user')
            ->willReturn(['id' => 1]);

        $response = new Response();

        $result = $this->userController->deleteUser($request, $response);

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}


Note: This code assumes that the `UserController` class has methods `getAllUsers`, `createUser`, `updateUser`, and `deleteUser` which handle the respective CRUD operations. The `getAllUsers` method is expected to return a `JsonResponse` object, while the `createUser`, `updateUser`, and `deleteUser` methods are expected to return a `JsonResponse` object as well. The `PDO` object is mocked to simulate database interactions.