<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\UserRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $userRepository;
    private $connectionMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository, $this->connectionMock);
    }

    public function testLoginSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->connectionMock->expects($this->once())
            ->method('rollBack')
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'test_user';
        $password = 'wrong_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'new_user';
        $password = 'new_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->connectionMock->expects($this->once())
            ->method('insert')
            ->with('users', [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ])
            ->willReturn(true);

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'new_user';
        $password = 'new_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authService->register($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can log in successfully.
- `testLoginFailure`: Tests that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Tests that a user can register successfully.
- `testRegisterFailure`: Tests that a user cannot register with an existing username.

Each test method uses PHPUnit's `createMock` method to create mock objects for the `UserRepository` and `Connection` classes. The `expects` method is used to define the expected behavior of these mock objects. The `willReturn` method is used to define the return values of the mock objects. The `assertTrue` and `assertFalse` methods are used to assert the expected behavior of the `AuthService` class.