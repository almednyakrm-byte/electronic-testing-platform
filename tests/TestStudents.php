<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class TestStudents extends TestCase
{
    private $studentsController;
    private $studentsRepository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->studentsRepository = $this->createMock(StudentsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->studentsController = new StudentsController(
            $this->studentsRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetAllStudents()
    {
        $students = [
            new Student(1, 'John Doe', 20),
            new Student(2, 'Jane Doe', 22),
        ];

        $this->studentsRepository
            ->method('findAll')
            ->willReturn($students);

        $response = $this->studentsController->getAllStudents();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($students), $response->getContent());
    }

    public function testGetStudentById()
    {
        $student = new Student(1, 'John Doe', 20);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $response = $this->studentsController->getStudentById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testGetStudentByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->studentsController->getStudentById(1);
    }

    public function testCreateStudent()
    {
        $student = new Student(1, 'John Doe', 20);

        $this->studentsRepository
            ->method('save')
            ->with($student)
            ->willReturn($student);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('age', 20);

        $response = $this->studentsController->createStudent($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudent()
    {
        $student = new Student(1, 'John Doe', 20);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $this->studentsRepository
            ->method('save')
            ->with($student)
            ->willReturn($student);

        $request = new Request();
        $request->request->set('name', 'Jane Doe');
        $request->request->set('age', 22);

        $response = $this->studentsController->updateStudent(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Jane Doe');
        $request->request->set('age', 22);

        $this->studentsController->updateStudent(1, $request);
    }

    public function testDeleteStudent()
    {
        $student = new Student(1, 'John Doe', 20);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $response = $this->studentsController->deleteStudent(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->studentsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->studentsController->deleteStudent(1);
    }
}


This test file covers the following scenarios:

*   `testGetAllStudents`: Tests the `getAllStudents` method to ensure it returns a list of students.
*   `testGetStudentById`: Tests the `getStudentById` method to ensure it returns a student by ID.
*   `testGetStudentByIdNotFound`: Tests the `getStudentById` method to ensure it throws a `NotFoundHttpException` when the student is not found.
*   `testCreateStudent`: Tests the `createStudent` method to ensure it creates a new student and returns it.
*   `testUpdateStudent`: Tests the `updateStudent` method to ensure it updates an existing student and returns it.
*   `testUpdateStudentNotFound`: Tests the `updateStudent` method to ensure it throws a `NotFoundHttpException` when the student is not found.
*   `testDeleteStudent`: Tests the `deleteStudent` method to ensure it deletes a student.
*   `testDeleteStudentNotFound`: Tests the `deleteStudent` method to ensure it throws a `NotFoundHttpException` when the student is not found.