<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialController;
use App\Repository\MaterialRepository;
use App\Entity\Material;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestMaterialController extends TestCase
{
    private $materialController;
    private $materialRepository;
    private $router;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->materialRepository = $this->createMock(MaterialRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->pdoMock = $this->createMock(\PDO::class);

        $this->materialController = new MaterialController($this->materialRepository, $this->router, $this->pdoMock);
    }

    public function testGetMaterials(): void
    {
        $this->materialRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Material()]);

        $response = $this->materialController->getMaterials();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetMaterial(): void
    {
        $material = new Material();
        $this->materialRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $response = $this->materialController->getMaterial(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateMaterial(): void
    {
        $material = new Material();
        $this->materialRepository->expects($this->once())
            ->method('save')
            ->with($material);

        $request = new Request([], [], ['material' => ['name' => 'Material Name']]);
        $response = $this->materialController->createMaterial($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMaterial(): void
    {
        $material = new Material();
        $this->materialRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);
        $this->materialRepository->expects($this->once())
            ->method('save')
            ->with($material);

        $request = new Request([], [], ['material' => ['name' => 'Material Name']]);
        $response = $this->materialController->updateMaterial(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaterial(): void
    {
        $material = new Material();
        $this->materialRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);
        $this->materialRepository->expects($this->once())
            ->method('remove')
            ->with($material);

        $response = $this->materialController->deleteMaterial(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// MaterialController.php
namespace App\Controller;

use App\Repository\MaterialRepository;
use App\Entity\Material;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class MaterialController
{
    private $materialRepository;
    private $router;
    private $pdo;

    public function __construct(
        MaterialRepository $materialRepository,
        RouterInterface $router,
        \PDO $pdo
    ) {
        $this->materialRepository = $materialRepository;
        $this->router = $router;
        $this->pdo = $pdo;
    }

    public function getMaterials(): Response
    {
        // implementation
    }

    public function getMaterial(int $id): Response
    {
        // implementation
    }

    public function createMaterial(Request $request): Response
    {
        // implementation
    }

    public function updateMaterial(int $id, Request $request): Response
    {
        // implementation
    }

    public function deleteMaterial(int $id): Response
    {
        // implementation
    }
}