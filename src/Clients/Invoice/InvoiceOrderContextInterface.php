<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;
use DateTimeInterface;

interface InvoiceOrderContextInterface
{
    public function getOrderNumber(): string;
    public function getOrderInvoiceNumber(): string;
    public function setOrderInvoiceNumber(string $invoiceNumber): void;
    public function getOrderDateTime(): DateTimeInterface;
    public function getPersonalData(): CustomerDataDto;
    public function getInvoiceAddress(): InvoiceAddressDto;
    public function getDeliveryAddress(): DeliveryAddressDto;
    public function getBasket(): BasketDto;
    public function getRefundBasket(): RefundBasketDto;
    public function getCreateInvoiceBasket(): CreateInvoiceBasketDto;
    public function getShippingBasketPositions(): ShippingBasketPositionDtoCollection;
    public function getPreCheckResponseData(): array;
    public function setPreCheckResponseData(array $data): void;
    public function getReturnPositions(): ReturnPositionModelDtoCollection;
}
