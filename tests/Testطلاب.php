<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new طلابController($this->repository, $this->router);
    }

    public function testGetAll(): void
    {
        $expectedResponse = new Response(json_encode([new طلاب()]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new طلاب()]);

        $response = $this->controller->getAll();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne(): void
    {
        $expectedResponse = new Response(json_encode(new طلاب()));

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $response = $this->controller->getOne(1);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate(): void
    {
        $expectedResponse = new Response(json_encode(new طلاب()));

        $request = new Request([], [], [], [], [], ['json' => ['name' => 'John', 'age' => 20]]);

        $this->repository->expects($this->once())
            ->method('create')
            ->with(new طلاب('John', 20))
            ->willReturn(new طلاب());

        $response = $this->controller->create($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $expectedResponse = new Response(json_encode(new طلاب()));

        $request = new Request([], [], [], [], [], ['json' => ['name' => 'John', 'age' => 21]]);

        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new طلاب('John', 21))
            ->willReturn(new طلاب());

        $response = $this->controller->update(1, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete(): void
    {
        $expectedResponse = new Response('', Response::HTTP_NO_CONTENT);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(null);

        $response = $this->controller->delete(1);

        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\طلابController.php

namespace App\Controller;

use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class طلابController
{
    private $repository;
    private $router;

    public function __construct(طلابRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getAll(): Response
    {
        return new Response(json_encode($this->repository->findAll()));
    }

    public function getOne(int $id): Response
    {
        return new Response(json_encode($this->repository->find($id)));
    }

    public function create(Request $request): Response
    {
        $student = new طلاب();
        $student->setName($request->get('name'));
        $student->setAge($request->get('age'));
        $this->repository->create($student);
        return new Response(json_encode($student));
    }

    public function update(int $id, Request $request): Response
    {
        $student = $this->repository->find($id);
        $student->setName($request->get('name'));
        $student->setAge($request->get('age'));
        $this->repository->update($id, $student);
        return new Response(json_encode($student));
    }

    public function delete(int $id): Response
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}