<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\TeachersController;
use App\Repository\TeachersRepository;
use App\Entity\Teacher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class TestTeachers extends TestCase
{
    private $teachersController;
    private $teachersRepository;
    private $router;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->teachersRepository = $this->createMock(TeachersRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->teachersController = new TeachersController($this->teachersRepository, $this->router);
    }

    public function testGetTeachers()
    {
        $expectedTeachers = [
            new Teacher('John Doe', 'john@example.com'),
            new Teacher('Jane Doe', 'jane@example.com'),
        ];

        $this->teachersRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedTeachers);

        $response = $this->teachersController->getTeachers();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedTeachers), $response->getContent());
    }

    public function testGetTeacherById()
    {
        $teacherId = 1;
        $expectedTeacher = new Teacher('John Doe', 'john@example.com');

        $this->teachersRepository->expects($this->once())
            ->method('find')
            ->with($teacherId)
            ->willReturn($expectedTeacher);

        $response = $this->teachersController->getTeacher($teacherId);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedTeacher), $response->getContent());
    }

    public function testGetTeacherByIdNotFound()
    {
        $teacherId = 1;

        $this->teachersRepository->expects($this->once())
            ->method('find')
            ->with($teacherId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->teachersController->getTeacher($teacherId);
    }

    public function testCreateTeacher()
    {
        $teacherData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $expectedTeacher = new Teacher($teacherData['name'], $teacherData['email']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $expectedTeacher->getName(), 'email' => $expectedTeacher->getEmail()]);

        $response = $this->teachersController->createTeacher($teacherData);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedTeacher), $response->getContent());
    }

    public function testUpdateTeacher()
    {
        $teacherId = 1;
        $teacherData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $expectedTeacher = new Teacher($teacherData['name'], $teacherData['email']);

        $this->teachersRepository->expects($this->once())
            ->method('find')
            ->with($teacherId)
            ->willReturn($expectedTeacher);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $expectedTeacher->getName(), 'email' => $expectedTeacher->getEmail(), 'id' => $teacherId]);

        $response = $this->teachersController->updateTeacher($teacherId, $teacherData);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedTeacher), $response->getContent());
    }

    public function testDeleteTeacher()
    {
        $teacherId = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $teacherId]);

        $response = $this->teachersController->deleteTeacher($teacherId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}