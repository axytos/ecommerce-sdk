<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlCheckRequestDto implements DtoInterface
{
    public string $requestMode;
    public string $proofOfInterest;
    public string $paymentTypeSecurity;
    public CustomerDataDto $personalData;
    public InvoiceAddressDto $invoiceAddress;
    public DeliveryAddressDto $deliveryAddress;
    public PaymentControlBasketDto $basket;
    public ?PaymentControlCheckResponseDto $paymentControlResponse = null;
}
