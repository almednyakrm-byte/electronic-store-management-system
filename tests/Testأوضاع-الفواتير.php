<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;
use PDOStatement;

class Testأوضاعالفواتير extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetAllأوضاعالفواتير(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM أوضاع_الفواتير')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'أوضاع الفواتير 1'],
                ['id' => 2, 'name' => 'أوضاع الفواتير 2'],
            ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'name' => 'أوضاع الفواتير 1'],
                ['id' => 2, 'name' => 'أوضاع الفواتير 2'],
            ]));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetأوضاعالفواتيرById(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM أوضاع_الفواتير WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'أوضاع الفواتير 1']);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'أوضاع الفواتير 1']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateأوضاعالفواتير(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO أوضاع_الفواتير (name) VALUES (:name)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'أوضاع الفواتير 3');

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(3);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'أوضاع الفواتير 3']);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 3, 'name' => 'أوضاع الفواتير 3']));

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateأوضاعالفواتير(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE أوضاع_الفواتير SET name = :name WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'أوضاع الفواتير 1 updated');

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'أوضاع الفواتير 1 updated']);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'أوضاع الفواتير 1 updated']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteأوضاعالفواتير(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM أوضاع_الفواتير WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $this->assertEquals(204, $response->getStatusCode());
    }
}