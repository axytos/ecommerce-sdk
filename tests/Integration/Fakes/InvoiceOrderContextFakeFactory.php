<?php

namespace Axytos\ECommerce\Tests\Integration\Fakes;

use Axytos\ECommerce\DataMapping\DtoToDtoMapper;
use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDtoCollection;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\RefundBasketTaxGroupDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;

class InvoiceOrderContextFakeFactory
{
    /**
     * @return InvoiceOrderContextFake
     */
    public function createInvoiceOrderContext()
    {
        $orderContext = new InvoiceOrderContextFake();

        $orderContext->setOrderNumber($this->createOrderNumber());
        $orderContext->setOrderInvoiceNumber($this->createOrderInvoiceNumber());
        $orderContext->setOrderDateTime($this->createOrderDateTime());
        $orderContext->setPersonalData($this->createPersonalData());
        $orderContext->setInvoiceAddress($this->createInvoiceAddress());
        $orderContext->setDeliveryAddress($this->createDeliveryAddress());

        $basket = $this->createBasket();
        $orderContext->setBasket($basket);
        $orderContext->setCreateInvoiceBasket($this->createCreateInvoiceBasket($basket));
        $orderContext->setShippingBasketPositions($this->createShippingBasketPositions($basket));
        $orderContext->setReturnPositions($this->createReturnPositions($basket));
        $orderContext->setRefundBasket($this->createRefundBasket($basket));

        $orderContext->setDeliveryWeight(42.6);
        $orderContext->setTrackingIds(['trackingId']);
        $orderContext->setLogistician('logistician');
        $orderContext->setDeliveryInformation('deliveryInformation');

        return $orderContext;
    }

    /**
     * @return string
     */
    public function createOrderNumber()
    {
        return uniqid('integration-test-order-number-');
    }

    /**
     * @return string
     */
    public function createOrderInvoiceNumber()
    {
        return uniqid('integration-test-order-invoice-number-');
    }

    /**
     * @return \DateTimeInterface
     */
    public function createOrderDateTime()
    {
        return new \DateTime();
    }

    /**
     * @return CustomerDataDto
     */
    public function createPersonalData()
    {
        $dto = new CustomerDataDto();
        $dto->externalCustomerId = 'ecommerce-sdk-integration-test-customer';
        $dto->email = 'ecommerce-sdk-integration-test-customer@axytos.com';

        return $dto;
    }

    /**
     * @return InvoiceAddressDto
     */
    public function createInvoiceAddress()
    {
        $dto = new InvoiceAddressDto();
        $dto->firstname = 'firstname';
        $dto->lastname = 'lastname';
        $dto->zipCode = '11111'; // required for risk suit mocking
        $dto->city = 'city';
        $dto->region = 'region';
        $dto->country = 'DE';
        $dto->addressLine1 = 'addressLine1';

        return $dto;
    }

    /**
     * @return DeliveryAddressDto
     */
    public function createDeliveryAddress()
    {
        $dto = new DeliveryAddressDto();
        $dto->firstname = 'firstname';
        $dto->lastname = 'lastname';
        $dto->zipCode = '11111'; // required for risk suit mocking
        $dto->city = 'city';
        $dto->region = 'region';
        $dto->country = 'DE';
        $dto->addressLine1 = 'addressLine1';

        return $dto;
    }

    /**
     * @return BasketDto
     */
    public function createBasket()
    {
        $positions = [];
        $positions[0] = $this->createBasketPosition();
        $positions[1] = $this->createBasketPosition();

        $dto = new BasketDto();
        $dto->currency = 'EUR';
        $dto->positions = new BasketPositionDtoCollection(...$positions);
        $dto->netTotal = array_sum(array_map(function (BasketPositionDto $position) {
            return $position->netPositionTotal;
        }, $positions));
        $dto->grossTotal = array_sum(array_map(function (BasketPositionDto $position) {
            return $position->grossPositionTotal;
        }, $positions));

        return $dto;
    }

    /**
     * @return BasketPositionDto
     */
    public function createBasketPosition()
    {
        $dto = new BasketPositionDto();
        $dto->productId = uniqid('productId-');
        $dto->productName = uniqid('productName-');
        $dto->quantity = 1.1;
        $dto->taxPercent = 19;
        $dto->netPricePerUnit = 42;
        $dto->netPositionTotal = $dto->quantity * $dto->netPricePerUnit;
        $dto->grossPricePerUnit = $dto->netPricePerUnit * 1.19;
        $dto->grossPositionTotal = $dto->quantity * $dto->grossPricePerUnit;

        return $dto;
    }

    /**
     * @param BasketDto $basket
     *
     * @return CreateInvoiceBasketDto
     */
    public function createCreateInvoiceBasket($basket)
    {
        $mapper = new DtoToDtoMapper();

        $dto = new CreateInvoiceBasketDto();
        $dto->netTotal = $basket->netTotal;
        $dto->grossTotal = $basket->grossTotal;
        $dto->positions = $mapper->mapDtoCollection($basket->positions, CreateInvoiceBasketPositionDtoCollection::class);
        $dto->taxGroups = new CreateInvoiceTaxGroupDtoCollection(...[]);

        return $dto;
    }

    /**
     * @param BasketDto $basket
     *
     * @return ShippingBasketPositionDtoCollection
     */
    public function createShippingBasketPositions($basket)
    {
        $mapper = new DtoToDtoMapper();

        return $mapper->mapDtoCollection($basket->positions, ShippingBasketPositionDtoCollection::class);
    }

    /**
     * @param BasketDto $basket
     *
     * @return ReturnPositionModelDtoCollection
     */
    public function createReturnPositions($basket)
    {
        $positions = array_map(function (BasketPositionDto $position) {
            $dto = new ReturnPositionModelDto();
            $dto->quantityToReturn = floatval($position->quantity);
            $dto->productId = strval($position->productId);

            return $dto;
        }, $basket->positions->getElements());

        return new ReturnPositionModelDtoCollection(...$positions);
    }

    /**
     * @param BasketDto $basket
     *
     * @return RefundBasketDto
     */
    public function createRefundBasket($basket)
    {
        $positions = array_map(function (BasketPositionDto $position) {
            $dto = new RefundBasketPositionDto();
            $dto->productId = $position->productId;
            $dto->netRefundTotal = $position->netPositionTotal;
            $dto->grossRefundTotal = $position->grossPositionTotal;

            return $dto;
        }, $basket->positions->getElements());

        $dto = new RefundBasketDto();
        $dto->netTotal = $basket->netTotal;
        $dto->grossTotal = $basket->grossTotal;
        $dto->positions = new RefundBasketPositionDtoCollection(...$positions);
        $dto->taxGroups = new RefundBasketTaxGroupDtoCollection(...[]);

        return $dto;
    }
}
