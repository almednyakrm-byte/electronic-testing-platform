<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمادة_فنية extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمادة_فنية()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مادة_فنية')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مادة فنية']]);

        $result = $this->pdo->query('SELECT * FROM مادة_فنية')->fetchAll();
        $this->assertEquals([['id' => 1, 'name' => 'مادة فنية']], $result);
    }

    public function testPostمادة_فنية()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مادة_فنية (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'مادة فنية جديدة');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('INSERT INTO مادة_فنية (name) VALUES (:name)');
        $this->pdo->bindParam(':name', 'مادة فنية جديدة');
        $this->assertTrue($this->pdo->execute());
    }

    public function testPutمادة_فنية()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مادة_فنية SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'مادة فنية محدثة');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('UPDATE مادة_فنية SET name = :name WHERE id = :id');
        $this->pdo->bindParam(':name', 'مادة فنية محدثة');
        $this->pdo->bindParam(':id', 1);
        $this->assertTrue($this->pdo->execute());
    }

    public function testDeleteمادة_فنية()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مادة_فنية WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('DELETE FROM مادة_فنية WHERE id = :id');
        $this->pdo->bindParam(':id', 1);
        $this->assertTrue($this->pdo->execute());
    }
}