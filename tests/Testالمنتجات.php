<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use App\Service\ProductsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالمنتجات extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ProductsRepository::class);
        $this->service = $this->createMock(ProductsService::class);
        $this->controller = new ProductsController($this->repository, $this->service);
    }

    public function testGetProducts()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $response = $this->controller->getProducts();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProductById()
    {
        $productId = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn(['id' => $productId, 'name' => 'Product 1']);

        $response = $this->controller->getProduct($productId);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProductByIdNotFound()
    {
        $productId = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getProduct($productId);
    }

    public function testCreateProduct()
    {
        $product = ['name' => 'Product 1'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($product)
            ->willReturn($product);

        $request = new Request([], [], ['json' => $product]);
        $response = $this->controller->createProduct($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProduct()
    {
        $productId = 1;
        $product = ['id' => $productId, 'name' => 'Product 1'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn($product);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($product)
            ->willReturn($product);

        $request = new Request([], [], ['json' => $product]);
        $response = $this->controller->updateProduct($productId, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProductNotFound()
    {
        $productId = 1;
        $product = ['id' => $productId, 'name' => 'Product 1'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateProduct($productId, new Request());
    }

    public function testDeleteProduct()
    {
        $productId = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn(['id' => $productId, 'name' => 'Product 1']);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($productId);

        $response = $this->controller->deleteProduct($productId);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProductNotFound()
    {
        $productId = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteProduct($productId);
    }
}