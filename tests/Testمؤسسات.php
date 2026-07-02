<?php

namespace App\Tests\Controller;

use App\Controller\InstitutesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestInstitutesController extends TestCase
{
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testGetInstitutes()
    {
        $controller = new InstitutesController($this->pdoMock);
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM institutes')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $controller->getInstitutes();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateInstitute()
    {
        $controller = new InstitutesController($this->pdoMock);
        $data = ['name' => 'Test Institute', 'address' => 'Test Address'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO institutes (name, address) VALUES (:name, :address)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $request = new Request([], [], ['data' => $data]);
        $response = $controller->createInstitute($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateInstitute()
    {
        $controller = new InstitutesController($this->pdoMock);
        $data = ['name' => 'Updated Test Institute', 'address' => 'Updated Test Address'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE institutes SET name = :name, address = :address WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $request = new Request([], [], ['data' => $data]);
        $response = $controller->updateInstitute(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteInstitute()
    {
        $controller = new InstitutesController($this->pdoMock);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM institutes WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $controller->deleteInstitute(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


This test file includes four test methods:

- `testGetInstitutes`: Tests the GET request to retrieve all institutes.
- `testCreateInstitute`: Tests the POST request to create a new institute.
- `testUpdateInstitute`: Tests the PUT request to update an existing institute.
- `testDeleteInstitute`: Tests the DELETE request to delete an institute.

Each test method creates a mock PDO object and sets up the expected behavior for the corresponding CRUD operation. The test then calls the corresponding method on the InstitutesController instance and asserts that the response is a JsonResponse with the expected status code.