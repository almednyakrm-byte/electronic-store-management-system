<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\PaymentController;
use App\Repository\PaymentRepository;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testدفع extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PaymentRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new PaymentController($this->repository, $this->entityManager);
    }

    public function testGetAllPayments()
    {
        $payments = [
            new Payment(),
            new Payment(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($payments);

        $response = $this->controller->getAllPayments();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($payments), $response->getContent());
    }

    public function testGetPaymentById()
    {
        $payment = new Payment();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $response = $this->controller->getPaymentById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($payment), $response->getContent());
    }

    public function testGetPaymentByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getPaymentById(1);
    }

    public function testCreatePayment()
    {
        $payment = new Payment();
        $payment->setId(1);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($payment)
            ->willReturn($payment);

        $response = $this->controller->createPayment($payment);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($payment), $response->getContent());
    }

    public function testUpdatePayment()
    {
        $payment = new Payment();
        $payment->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($payment)
            ->willReturn($payment);

        $response = $this->controller->updatePayment(1, $payment);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($payment), $response->getContent());
    }

    public function testUpdatePaymentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $payment = new Payment();
        $payment->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->updatePayment(1, $payment);
    }

    public function testDeletePayment()
    {
        $payment = new Payment();
        $payment->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($payment);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deletePayment(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeletePaymentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $payment = new Payment();
        $payment->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deletePayment(1);
    }
}


This test file covers all CRUD operations for the 'دفع' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

*   `testGetAllPayments`: Verifies that the `getAllPayments` method returns a list of all payments.
*   `testGetPaymentById`: Verifies that the `getPaymentById` method returns a payment by its ID.
*   `testGetPaymentByIdNotFound`: Verifies that the `getPaymentById` method throws a `NotFoundHttpException` when the payment is not found.
*   `testCreatePayment`: Verifies that the `createPayment` method creates a new payment and returns it.
*   `testUpdatePayment`: Verifies that the `updatePayment` method updates an existing payment and returns it.
*   `testUpdatePaymentNotFound`: Verifies that the `updatePayment` method throws a `NotFoundHttpException` when the payment is not found.
*   `testDeletePayment`: Verifies that the `deletePayment` method deletes a payment and returns a 204 status code.
*   `testDeletePaymentNotFound`: Verifies that the `deletePayment` method throws a `NotFoundHttpException` when the payment is not found.