<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ExamController;
use App\Repository\ExamRepository;
use App\Entity\Exam;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testاختبارات extends TestCase
{
    private $examController;
    private $examRepository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->examRepository = $this->createMock(ExamRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->examController = new ExamController(
            $this->examRepository,
            $this->entityManager,
            $this->router,
            $this->tokenStorage
        );
    }

    public function testGetExams()
    {
        $exams = [
            new Exam(),
            new Exam(),
            new Exam(),
        ];

        $this->examRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($exams);

        $response = $this->examController->getExams();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateExam()
    {
        $exam = new Exam();
        $exam->setName('Exam Name');
        $exam->setDescription('Exam Description');

        $this->examRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($exam));

        $request = new Request();
        $request->request->set('name', 'Exam Name');
        $request->request->set('description', 'Exam Description');

        $response = $this->examController->createExam($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateExam()
    {
        $exam = new Exam();
        $exam->setId(1);
        $exam->setName('Exam Name');
        $exam->setDescription('Exam Description');

        $this->examRepository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn($exam);

        $this->examRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($exam));

        $request = new Request();
        $request->request->set('name', 'Updated Exam Name');
        $request->request->set('description', 'Updated Exam Description');

        $response = $this->examController->updateExam(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteExam()
    {
        $exam = new Exam();
        $exam->setId(1);

        $this->examRepository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn($exam);

        $this->examRepository->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($exam));

        $response = $this->examController->deleteExam(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'اختبارات' module. It uses mocked PDO statements to simulate database interactions. The test cases cover the following scenarios:

1.  `testGetExams`: Tests the GET request to retrieve all exams.
2.  `testCreateExam`: Tests the POST request to create a new exam.
3.  `testUpdateExam`: Tests the PUT request to update an existing exam.
4.  `testDeleteExam`: Tests the DELETE request to delete an exam.

Each test case uses the `createMock` method to create a mock object for the `ExamRepository` and `EntityManagerInterface`. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.

The test cases also use the `Request` object to simulate the HTTP request and the `JsonResponse` object to simulate the HTTP response. The `assertEquals` method is used to verify that the response status code matches the expected status code.