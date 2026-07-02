<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialTestController;
use App\Repository\MaterialTestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;

class TestMaterialTestController extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $materialTestRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->materialTestRepository = $this->createMock(MaterialTestRepository::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new MaterialTestController(
            $this->router,
            $this->tokenStorage,
            $this->materialTestRepository,
            $this->pdo
        );
    }

    public function testGetMaterialTestList(): void
    {
        $this->materialTestRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Material Test 1'],
                ['id' => 2, 'name' => 'Material Test 2'],
            ]);

        $response = $this->controller->getMaterialTestList();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateMaterialTest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'Material Test 3']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO material_tests (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createMaterialTest($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMaterialTest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'Material Test 1 Updated']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE material_tests SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateMaterialTest(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteMaterialTest(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM material_tests WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteMaterialTest(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'مادة_اختبار' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes and response headers for each operation.