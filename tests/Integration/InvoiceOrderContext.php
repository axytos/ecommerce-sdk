<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;
use DateTimeInterface;

class InvoiceOrderContext implements InvoiceOrderContextInterface
{
    private string $orderNumber;
    private string $orderInvoiceNumber;
    private DateTimeInterface $orderDateTime;
    private CustomerDataDto $personalData;
    private InvoiceAddressDto $invoiceAddress;
    private DeliveryAddressDto $deliveryAddress;
    private BasketDto $basket;
    private RefundBasketDto $refundBasket;
    private CreateInvoiceBasketDto $createInvoiceBasket;
    private ShippingBasketPositionDtoCollection $shippingBasketPositions;
    private ReturnPositionModelDtoCollection $returnPositions;

    private array $preCheckResponseData = [];

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function getOrderInvoiceNumber(): string
    {
        return $this->orderInvoiceNumber;
    }

    public function setOrderInvoiceNumber(string $invoiceNumber): void
    {
        $this->orderInvoiceNumber = $invoiceNumber;
    }

    public function getOrderDateTime(): DateTimeInterface
    {
        return $this->orderDateTime;
    }

    public function setOrderDateTime(DateTimeInterface $orderDateTime): void
    {
        $this->orderDateTime = $orderDateTime;
    }

    public function getPersonalData(): CustomerDataDto
    {
        return $this->personalData;
    }

    public function setPersonalData(CustomerDataDto $personalData): void
    {
        $this->personalData = $personalData;
    }

    public function getInvoiceAddress(): InvoiceAddressDto
    {
        return $this->invoiceAddress;
    }

    public function setInvoiceAddress(InvoiceAddressDto $invoiceAddress): void
    {
        $this->invoiceAddress = $invoiceAddress;
    }

    public function getDeliveryAddress(): DeliveryAddressDto
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(DeliveryAddressDto $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function getBasket(): BasketDto
    {
        return $this->basket;
    }

    public function setBasket(BasketDto $basket): void
    {
        $this->basket = $basket;
    }

    public function getRefundBasket(): RefundBasketDto
    {
        return $this->refundBasket;
    }

    public function setRefundBasket(RefundBasketDto $refundBasket): void
    {
        $this->refundBasket = $refundBasket;
    }

    public function getCreateInvoiceBasket(): CreateInvoiceBasketDto
    {
        return $this->createInvoiceBasket;
    }

    public function setCreateInvoiceBasket(CreateInvoiceBasketDto $createInvoiceBasket): void
    {
        $this->createInvoiceBasket = $createInvoiceBasket;
    }

    public function getShippingBasketPositions(): ShippingBasketPositionDtoCollection
    {
        return $this->shippingBasketPositions;
    }

    public function setShippingBasketPositions(ShippingBasketPositionDtoCollection $shippingBasketPositions): void
    {
        $this->shippingBasketPositions = $shippingBasketPositions;
    }

    public function getPreCheckResponseData(): array
    {
        return $this->preCheckResponseData;
    }

    public function setPreCheckResponseData(array $data): void
    {
        $this->preCheckResponseData = $data;
    }

    public function getReturnPositions(): ReturnPositionModelDtoCollection
    {
        return $this->returnPositions;
    }

    public function setReturnPositions(ReturnPositionModelDtoCollection $returnPositions): void
    {
        $this->returnPositions = $returnPositions;
    }
}
