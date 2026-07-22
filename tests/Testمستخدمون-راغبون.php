<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمستخدمونراغبون extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetRequest(): void
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مستخدمون_راغبون')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $result = $this->pdo->query('SELECT * FROM مستخدمون_راغبون')->fetchAll();
        $this->assertCount(2, $result);
    }

    public function testPostRequest(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مستخدمون_راغبون (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'John Doe');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('INSERT INTO مستخدمون_راغبون (name) VALUES (:name)');
        $stmt->bindParam(':name', 'John Doe');
        $result = $stmt->execute();
        $this->assertTrue($result);
    }

    public function testPutRequest(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مستخدمون_راغبون SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Jane Doe');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('UPDATE مستخدمون_راغبون SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', 'Jane Doe');
        $stmt->bindParam(':id', 1);
        $result = $stmt->execute();
        $this->assertTrue($result);
    }

    public function testDeleteRequest(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مستخدمون_راغبون WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('DELETE FROM مستخدمون_راغبون WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $result = $stmt->execute();
        $this->assertTrue($result);
    }
}