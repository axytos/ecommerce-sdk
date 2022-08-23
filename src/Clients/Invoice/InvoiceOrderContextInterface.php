<?php declare(strict_types=1);

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
    function getOrderNumber(): string;
    function getOrderInvoiceNumber(): string;
    function setOrderInvoiceNumber(string $invoiceNumber): void;
    function getOrderDateTime(): DateTimeInterface;
    function getPersonalData(): CustomerDataDto;
    function getInvoiceAddress(): InvoiceAddressDto;
    function getDeliveryAddress(): DeliveryAddressDto;
    function getBasket(): BasketDto;
    function getRefundBasket(): RefundBasketDto;
    function getCreateInvoiceBasket(): CreateInvoiceBasketDto;
    function getShippingBasketPositions(): ShippingBasketPositionDtoCollection;
    function getPreCheckResponseData(): array;
    function setPreCheckResponseData(array $data): void;
    function getReturnPositions(): ReturnPositionModelDtoCollection;
}
