<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class Testمقررات extends TestCase
{
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
    }

    public function testGetمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'مقرر 1'],
            ['id' => 2, 'name' => 'مقرر 2'],
        ]);

        $this->mockPDO->method('prepare')->willReturn($mockStatement);

        $controller = new المقرراتController($this->mockPDO);
        $result = $controller->getمقررات($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn(['name' => 'مقرر جديد']);

        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('lastInsertId')->willReturn(1);

        $this->mockPDO->method('prepare')->willReturn($mockStatement);

        $controller = new المقرراتController($this->mockPDO);
        $result = $controller->postمقررات($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testPutمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn(['name' => 'مقرر محدث']);

        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->method('execute')->willReturn(true);

        $this->mockPDO->method('prepare')->willReturn($mockStatement);

        $controller = new المقرراتController($this->mockPDO);
        $result = $controller->putمقررات($request, $response, ['id' => 1]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->method('execute')->willReturn(true);

        $this->mockPDO->method('prepare')->willReturn($mockStatement);

        $controller = new المقرراتController($this->mockPDO);
        $result = $controller->deletemقررات($request, $response, ['id' => 1]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }
}