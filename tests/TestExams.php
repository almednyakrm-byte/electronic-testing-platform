<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ExamsController;
use App\Repository\ExamsRepository;
use App\Service\ExamsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestExams extends TestCase
{
    private $examsController;
    private $examsRepository;
    private $examsService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->examsRepository = $this->createMock(ExamsRepository::class);
        $this->examsService = $this->createMock(ExamsService::class);
        $this->examsController = new ExamsController($this->examsRepository, $this->examsService);
    }

    public function testGetExams()
    {
        $this->examsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Exam 1'],
                ['id' => 2, 'name' => 'Exam 2'],
            ]);

        $response = $this->examsController->getExams();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['exams' => [
            ['id' => 1, 'name' => 'Exam 1'],
            ['id' => 2, 'name' => 'Exam 2'],
        ]], json_decode($response->getContent(), true));
    }

    public function testGetExamById()
    {
        $this->examsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Exam 1']);

        $response = $this->examsController->getExamById(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['exam' => ['id' => 1, 'name' => 'Exam 1']], json_decode($response->getContent(), true));
    }

    public function testGetExamByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->examsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->examsController->getExamById(1);
    }

    public function testCreateExam()
    {
        $this->examsService->expects($this->once())
            ->method('createExam')
            ->with(['name' => 'Exam 1'])
            ->willReturn(['id' => 1, 'name' => 'Exam 1']);

        $request = new Request([], [], ['name' => 'Exam 1']);
        $response = $this->examsController->createExam($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['exam' => ['id' => 1, 'name' => 'Exam 1']], json_decode($response->getContent(), true));
    }

    public function testUpdateExam()
    {
        $this->examsService->expects($this->once())
            ->method('updateExam')
            ->with(1, ['name' => 'Exam 1'])
            ->willReturn(['id' => 1, 'name' => 'Exam 1']);

        $request = new Request([], [], ['name' => 'Exam 1']);
        $response = $this->examsController->updateExam(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['exam' => ['id' => 1, 'name' => 'Exam 1']], json_decode($response->getContent(), true));
    }

    public function testUpdateExamNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->examsService->expects($this->once())
            ->method('updateExam')
            ->with(1, ['name' => 'Exam 1'])
            ->willReturn(null);

        $request = new Request([], [], ['name' => 'Exam 1']);
        $this->examsController->updateExam(1, $request);
    }

    public function testDeleteExam()
    {
        $this->examsService->expects($this->once())
            ->method('deleteExam')
            ->with(1);

        $response = $this->examsController->deleteExam(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteExamNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->examsService->expects($this->once())
            ->method('deleteExam')
            ->with(1)
            ->willReturn(null);

        $this->examsController->deleteExam(1);
    }
}