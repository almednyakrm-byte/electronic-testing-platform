<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمستخدمون extends TestCase
{
    private MockObject $pdo;
    private MockObject $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمستخدمون(): void
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مستخدمون')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $result = $this->getمستخدمون();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testPostمستخدمون(): void
    {
        $data = ['name' => 'New User'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مستخدمون (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->postمستخدمون($data);
        $this->assertTrue($result);
    }

    public function testPutمستخدمون(): void
    {
        $id = 1;
        $data = ['name' => 'Updated User'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مستخدمون SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putمستخدمون($id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteمستخدمون(): void
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مستخدمون WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteمستخدمون($id);
        $this->assertTrue($result);
    }

    private function getمستخدمون(): array
    {
        $this->pdo->query('SELECT * FROM مستخدمون');
        return $this->stmt->fetchAll();
    }

    private function postمستخدمون(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO مستخدمون (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function putمستخدمون(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE مستخدمون SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteمستخدمون(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM مستخدمون WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}