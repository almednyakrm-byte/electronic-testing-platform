<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\CoursesController;
use App\Repository\CoursesRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestCourses extends TestCase
{
    private $coursesController;
    private $coursesRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->coursesRepository = $this->createMock(CoursesRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->coursesController = new CoursesController($this->coursesRepository, $this->pdo);
    }

    public function testGetCourses()
    {
        $courses = [
            ['id' => 1, 'name' => 'Course 1'],
            ['id' => 2, 'name' => 'Course 2'],
        ];

        $this->coursesRepository->expects($this->once())
            ->method('getAllCourses')
            ->willReturn($courses);

        $response = $this->coursesController->getCourses();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getBody()->getContents());
    }

    public function testCreateCourse()
    {
        $course = ['id' => 3, 'name' => 'Course 3'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO courses (name) VALUES (:name)', ['name' => $course['name']]);

        $response = $this->coursesController->createCourse($course);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getBody()->getContents());
    }

    public function testUpdateCourse()
    {
        $course = ['id' => 1, 'name' => 'Updated Course 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE courses SET name = :name WHERE id = :id', ['name' => $course['name'], 'id' => $course['id']]);

        $response = $this->coursesController->updateCourse($course);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getBody()->getContents());
    }

    public function testDeleteCourse()
    {
        $courseId = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM courses WHERE id = :id', ['id' => $courseId]);

        $response = $this->coursesController->deleteCourse($courseId);

        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'courses' module. It uses mocked PDO statements to simulate database interactions. The tests verify the expected HTTP status codes and response bodies for each operation.