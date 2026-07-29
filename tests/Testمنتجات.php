<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use App\Service\ProductsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testمنتجات extends TestCase
{
    private $productsController;
    private $productsRepository;
    private $productsService;
    private $router;

    protected function setUp(): void
    {
        $this->productsRepository = $this->createMock(ProductsRepository::class);
        $this->productsService = $this->createMock(ProductsService::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->productsController = new ProductsController(
            $this->productsRepository,
            $this->productsService,
            $this->router
        );
    }

    public function testGetProducts()
    {
        $this->productsRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $response = $this->productsController->getProducts();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testCreateProduct()
    {
        $request = new Request([], [], ['name' => 'Product 3']);
        $this->productsService
            ->expects($this->once())
            ->method('create')
            ->with(['name' => 'Product 3'])
            ->willReturn(['id' => 3, 'name' => 'Product 3']);

        $response = $this->productsController->createProduct($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateProduct()
    {
        $request = new Request([], [], ['name' => 'Product 1 Updated']);
        $this->productsRepository
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Product 1']);

        $this->productsService
            ->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Product 1 Updated'])
            ->willReturn(['id' => 1, 'name' => 'Product 1 Updated']);

        $response = $this->productsController->updateProduct(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testDeleteProduct()
    {
        $this->productsRepository
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Product 1']);

        $this->productsService
            ->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->productsController->deleteProduct(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  **GET Products**: Tests the `getProducts` method of the `ProductsController` to ensure it returns a list of products in JSON format.
2.  **Create Product**: Tests the `createProduct` method to ensure it creates a new product and returns it in JSON format with a 201 status code.
3.  **Update Product**: Tests the `updateProduct` method to ensure it updates an existing product and returns it in JSON format with a 200 status code.
4.  **Delete Product**: Tests the `deleteProduct` method to ensure it deletes a product successfully and returns a 204 status code.

Each test method uses PHPUnit's mocking capabilities to simulate the behavior of the `ProductsRepository` and `ProductsService` classes. The `createMock` method is used to create mock objects for these classes, and the `expects` method is used to specify the expected behavior of these mocks.