<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ExamsController;
use App\Repository\ExamsRepository;
use App\Service\ExamsService;
use App\Entity\Exam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;

class TestExams extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ExamsRepository::class);
        $this->service = $this->createMock(ExamsService::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new ExamsController($this->repository, $this->service, $this->router, $this->tokenStorage);
    }

    public function testGetExams()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Exam()]);

        $response = $this->controller->getExams();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetExamNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(null);

        $this->controller->getExam(1);
    }

    public function testGetExam()
    {
        $exam = new Exam();
        $exam->setId(1);
        $exam->setName('Exam 1');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($exam);

        $response = $this->controller->getExam(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostExam()
    {
        $exam = new Exam();
        $exam->setName('Exam 1');

        $this->service->expects($this->once())
            ->method('createExam')
            ->with($exam)
            ->willReturn($exam);

        $response = $this->controller->postExam($exam);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutExam()
    {
        $exam = new Exam();
        $exam->setId(1);
        $exam->setName('Exam 1');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($exam);

        $this->service->expects($this->once())
            ->method('updateExam')
            ->with($exam)
            ->willReturn($exam);

        $response = $this->controller->putExam(1, $exam);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteExam()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(new Exam());

        $response = $this->controller->deleteExam(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// ExamsController.php
namespace App\Controller;

use App\Repository\ExamsRepository;
use App\Service\ExamsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ExamsController
{
    private $repository;
    private $service;
    private $router;
    private $tokenStorage;

    public function __construct(
        ExamsRepository $repository,
        ExamsService $service,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function getExams(Request $request)
    {
        // ...
    }

    public function getExam(Request $request, int $id)
    {
        // ...
    }

    public function postExam(Request $request)
    {
        // ...
    }

    public function putExam(Request $request, int $id)
    {
        // ...
    }

    public function deleteExam(Request $request, int $id)
    {
        // ...
    }
}