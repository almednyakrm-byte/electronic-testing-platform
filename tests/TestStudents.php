<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use App\Entity\Student;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestStudents extends TestCase
{
    private $studentsController;
    private $studentsRepository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->studentsRepository = $this->createMock(StudentsRepository::class);
        $this->studentsController = new StudentsController($this->studentsRepository);
    }

    public function testGetStudents()
    {
        $students = [
            new Student(1, 'John Doe'),
            new Student(2, 'Jane Doe'),
        ];

        $this->studentsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($students);

        $response = $this->studentsController->getStudents();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($students), $response->getContent());
    }

    public function testGetStudent()
    {
        $student = new Student(1, 'John Doe');

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $response = $this->studentsController->getStudent(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testGetStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->studentsController->getStudent(1);
    }

    public function testCreateStudent()
    {
        $student = new Student(1, 'John Doe');
        $data = ['name' => 'John Doe'];

        $this->studentsRepository->expects($this->once())
            ->method('save')
            ->with($student);

        $request = new Request();
        $request->request->set('name', $data['name']);

        $response = $this->studentsController->createStudent($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudent()
    {
        $student = new Student(1, 'John Doe');
        $data = ['name' => 'Jane Doe'];

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $this->studentsRepository->expects($this->once())
            ->method('save')
            ->with($student);

        $request = new Request();
        $request->request->set('name', $data['name']);

        $response = $this->studentsController->updateStudent(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $data = ['name' => 'Jane Doe'];

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', $data['name']);

        $this->studentsController->updateStudent(1, $request);
    }

    public function testDeleteStudent()
    {
        $student = new Student(1, 'John Doe');

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($student);

        $this->studentsRepository->expects($this->once())
            ->method('remove')
            ->with($student);

        $response = $this->studentsController->deleteStudent(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->studentsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->studentsController->deleteStudent(1);
    }
}


This test file covers the following scenarios:

- `testGetStudents`: Verifies that the `getStudents` method returns a list of students.
- `testGetStudent`: Verifies that the `getStudent` method returns a single student.
- `testGetStudentNotFound`: Verifies that the `getStudent` method throws a `NotFoundHttpException` when the student is not found.
- `testCreateStudent`: Verifies that the `createStudent` method creates a new student.
- `testUpdateStudent`: Verifies that the `updateStudent` method updates an existing student.
- `testUpdateStudentNotFound`: Verifies that the `updateStudent` method throws a `NotFoundHttpException` when the student is not found.
- `testDeleteStudent`: Verifies that the `deleteStudent` method deletes a student.
- `testDeleteStudentNotFound`: Verifies that the `deleteStudent` method throws a `NotFoundHttpException` when the student is not found.