<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlConfirmRequestDto implements DtoInterface
{
    public string $paymentTypeSecurity;
    public CustomerDataDto $personalData;
    public InvoiceAddressDto $invoiceAddress;
    public DeliveryAddressDto $deliveryAddress;
    public PaymentControlBasketDto $basket;
    public ?PaymentControlCheckResponseDto $paymentControlResponse = null;
}