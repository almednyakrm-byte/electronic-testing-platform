<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeachersController;
use App\Repository\TeachersRepository;
use App\Service\TeachersService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestTeachers extends TestCase
{
    private $teachersController;
    private $teachersRepository;
    private $teachersService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->teachersRepository = $this->createMock(TeachersRepository::class);
        $this->teachersService = $this->createMock(TeachersService::class);
        $this->teachersController = new TeachersController($this->teachersRepository, $this->teachersService);
    }

    public function testGetTeachers()
    {
        $expectedResponse = ['teachers' => []];
        $this->teachersRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);
        $response = $this->teachersController->getTeachers();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateTeacher()
    {
        $teacherData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher created successfully'];
        $this->teachersService->expects($this->once())
            ->method('create')
            ->with($teacherData)
            ->willReturn($expectedResponse);
        $response = $this->teachersController->createTeacher($teacherData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateTeacher()
    {
        $teacherId = 1;
        $teacherData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher updated successfully'];
        $this->teachersService->expects($this->once())
            ->method('update')
            ->with($teacherId, $teacherData)
            ->willReturn($expectedResponse);
        $response = $this->teachersController->updateTeacher($teacherId, $teacherData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteTeacher()
    {
        $teacherId = 1;
        $expectedResponse = ['message' => 'Teacher deleted successfully'];
        $this->teachersService->expects($this->once())
            ->method('delete')
            ->with($teacherId)
            ->willReturn($expectedResponse);
        $response = $this->teachersController->deleteTeacher($teacherId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// TeachersController.php

namespace App\Controller;

use App\Repository\TeachersRepository;
use App\Service\TeachersService;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeachersController
{
    private $teachersRepository;
    private $teachersService;

    public function __construct(TeachersRepository $teachersRepository, TeachersService $teachersService)
    {
        $this->teachersRepository = $teachersRepository;
        $this->teachersService = $teachersService;
    }

    public function getTeachers()
    {
        $teachers = $this->teachersRepository->getAll();
        return new JsonResponse($teachers);
    }

    public function createTeacher(array $data)
    {
        $teacher = $this->teachersService->create($data);
        return new JsonResponse(['message' => 'Teacher created successfully']);
    }

    public function updateTeacher(int $id, array $data)
    {
        $this->teachersService->update($id, $data);
        return new JsonResponse(['message' => 'Teacher updated successfully']);
    }

    public function deleteTeacher(int $id)
    {
        $this->teachersService->delete($id);
        return new JsonResponse(['message' => 'Teacher deleted successfully']);
    }
}



// TeachersRepository.php

namespace App\Repository;

class TeachersRepository
{
    public function getAll()
    {
        // Return all teachers from database
        return [];
    }
}



// TeachersService.php

namespace App\Service;

class TeachersService
{
    public function create(array $data)
    {
        // Create a new teacher in database
        return ['message' => 'Teacher created successfully'];
    }

    public function update(int $id, array $data)
    {
        // Update a teacher in database
        return ['message' => 'Teacher updated successfully'];
    }

    public function delete(int $id)
    {
        // Delete a teacher from database
        return ['message' => 'Teacher deleted successfully'];
    }
}