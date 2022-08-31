<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class OrderPreCheckRequestDto implements DtoInterface
{
    public string $requestMode;
    public string $proofOfInterest;
    public string $paymentTypeSecurity;
    public string $selectedPaymentType;
    public CustomerDataDto $personalData;
    public InvoiceAddressDto $invoiceAddress;
    public DeliveryAddressDto $deliveryAddress;
    public BasketDto $basket;
    public ?OrderPreCheckResponseDto $orderPrecheckResponse = null;
}
