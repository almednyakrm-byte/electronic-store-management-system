<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialController;
use App\Repository\MaterialRepository;
use App\Entity\Material;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testالمواد_الخام extends TestCase
{
    private $materialController;
    private $materialRepository;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->materialRepository = $this->createMock(MaterialRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->materialController = new MaterialController($this->materialRepository, $this->router);
    }

    public function testGetMaterials()
    {
        $materials = [
            new Material('Material 1', 'Description 1'),
            new Material('Material 2', 'Description 2'),
        ];

        $this->materialRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($materials);

        $response = $this->materialController->getMaterials($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getContent());
    }

    public function testCreateMaterial()
    {
        $material = new Material('Material 1', 'Description 1');

        $this->materialRepository->expects($this->once())
            ->method('save')
            ->with($material);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Material 1', 'description' => 'Description 1']);

        $response = $this->materialController->createMaterial($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMaterial()
    {
        $material = new Material('Material 1', 'Description 1');

        $this->materialRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $this->materialRepository->expects($this->once())
            ->method('save')
            ->with($material);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Material 2', 'description' => 'Description 2']);

        $response = $this->materialController->updateMaterial(1, $this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaterial()
    {
        $material = new Material('Material 1', 'Description 1');

        $this->materialRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($material);

        $this->materialRepository->expects($this->once())
            ->method('remove')
            ->with($material);

        $response = $this->materialController->deleteMaterial(1, $this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'المواد الخام' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

*   `testGetMaterials`: Tests the GET request to retrieve all materials.
*   `testCreateMaterial`: Tests the POST request to create a new material.
*   `testUpdateMaterial`: Tests the PUT request to update an existing material.
*   `testDeleteMaterial`: Tests the DELETE request to delete a material.

Each test method sets up the necessary mocks and calls the corresponding method on the `MaterialController` instance. It then asserts that the response is of the correct type and status code.