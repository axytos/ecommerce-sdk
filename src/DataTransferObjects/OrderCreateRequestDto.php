<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class OrderCreateRequestDto implements DtoInterface
{
    public string $externalOrderId;
    public DateTimeInterface $date;
    public CustomerDataDto $personalData;
    public InvoiceAddressDto $invoiceAddress;
    public DeliveryAddressDto $deliveryAddress;
    public BasketDto $basket;
    public OrderPreCheckResponseDto $orderPrecheckResponse;
}