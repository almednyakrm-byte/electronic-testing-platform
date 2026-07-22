<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authService = new AuthService($this->connection);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => $password]]));

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ? AND password = ?', [$username, $password])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => $password]]));

        $result = $this->authService->login($username, $password);
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn(new \ArrayIterator([['id' => 1, 'username' => $username, 'password' => 'wrongpassword']]));

        $result = $this->authService->login($username, $password);
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password]);

        $result = $this->authService->register($username, $password);
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willThrowException(new \Exception('Database error'));

        $result = $this->authService->register($username, $password);
        $this->assertFalse($result);
    }
}


This test file includes four test methods:

- `testLoginSuccess`: Tests a successful login by mocking the database to return a user with the correct password.
- `testLoginFailure`: Tests a failed login by mocking the database to return a user with the wrong password.
- `testRegisterSuccess`: Tests a successful registration by mocking the database to insert a new user.
- `testRegisterFailure`: Tests a failed registration by mocking the database to throw an exception when inserting a new user.