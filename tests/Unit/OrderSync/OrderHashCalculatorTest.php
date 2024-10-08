<?php

namespace Axytos\ECommerce\Tests\Unit\OrderSync;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDtoCollection;
use Axytos\ECommerce\OrderSync\HashAlgorithmInterface;
use Axytos\ECommerce\OrderSync\OrderHashCalculator;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class OrderHashCalculatorTest extends TestCase
{
    /**
     * @var HashAlgorithmInterface&MockObject
     */
    private $hashAlgorithm;

    /**
     * @var DtoArrayMapper&MockObject
     */
    private $dtoArrayMapper;

    /**
     * @var OrderHashCalculator
     */
    private $sut;

    /**
     * @var BasketDto
     */
    private $testBasket;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->hashAlgorithm = $this->createMock(HashAlgorithmInterface::class);
        $this->dtoArrayMapper = $this->createMock(DtoArrayMapper::class);

        $this->sut = new OrderHashCalculator(
            $this->hashAlgorithm,
            $this->dtoArrayMapper
        );

        $basketPosition1 = new BasketPositionDto();
        $basketPosition1->productId = '234432323';
        $basketPosition1->productName = 'Funny Toy';
        $basketPosition1->productCategory = 'Toys';
        $basketPosition1->quantity = 3.3;
        $basketPosition1->taxPercent = 0.19;
        $basketPosition1->netPricePerUnit = 10.0;
        $basketPosition1->grossPricePerUnit = 11.9;
        $basketPosition1->netPositionTotal = 30.0;
        $basketPosition1->grossPositionTotal = 35.7;

        $basketPosition2 = new BasketPositionDto();
        $basketPosition2->productId = '34452321';
        $basketPosition2->productName = 'Unfunny Toy';
        $basketPosition2->productCategory = 'Toys';
        $basketPosition2->quantity = 1.1;
        $basketPosition2->taxPercent = 0.19;
        $basketPosition2->netPricePerUnit = 100.0;
        $basketPosition2->grossPricePerUnit = 119.0;
        $basketPosition2->netPositionTotal = 100.0;
        $basketPosition2->grossPositionTotal = 119.0;

        $this->testBasket = new BasketDto();
        $this->testBasket->netTotal = 42.0;
        $this->testBasket->grossTotal = 60.0;
        $this->testBasket->currency = 'EUR';
        $this->testBasket->positions = new BasketPositionDtoCollection(
            $basketPosition1,
            $basketPosition2
        );
    }

    /**
     * @return void
     */
    public function test_compute_basket_hash_computes_hash_correctly()
    {
        $serializedData = [
            'prop1' => 1,
            'prop2' => 'data',
        ];
        $hashedData = 'computed hash';
        /** @var InvoiceOrderContextInterface&MockObject */
        $order = $this->createMock(InvoiceOrderContextInterface::class);

        $order->method('getBasket')->willReturn($this->testBasket);
        $this->dtoArrayMapper->method('toArray')->willReturn($serializedData);
        $this->hashAlgorithm->method('compute')->willReturn($hashedData);

        $order
            ->expects($this->once())
            ->method('getBasket')
        ;
        $this->dtoArrayMapper
            ->expects($this->once())
            ->method('toArray')
            ->with($this->testBasket)
        ;
        $this->hashAlgorithm
            ->expects($this->once())
            ->method('compute')
            ->with('a:2:{s:5:"prop1";i:1;s:5:"prop2";s:4:"data";}')
        ;

        $actual = $this->sut->computeBasketHash($order);
        $this->assertEquals($hashedData, $actual);
    }
}
