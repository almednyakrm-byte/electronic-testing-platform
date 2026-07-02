<?php

namespace App\Tests\Controller;

use App\Controller\MaterialController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestMaterialController extends TestCase
{
    private $materialController;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->materialController = new MaterialController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetMaterials()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM material')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->materialController->getMaterials();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateMaterial()
    {
        $material = [
            'name' => 'Material 1',
            'description' => 'This is a material',
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO material (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->materialController->createMaterial($material);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMaterial()
    {
        $materialId = 1;
        $material = [
            'name' => 'Material 1',
            'description' => 'This is a material',
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE material SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->materialController->updateMaterial($materialId, $material);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaterial()
    {
        $materialId = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM material WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->materialController->deleteMaterial($materialId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'مادة_تجريبية' module. It creates a mock instance of the MaterialController class and uses the `createMock` method to create mock objects for the PDO statements. The test methods cover the GET, POST, PUT, and DELETE requests.