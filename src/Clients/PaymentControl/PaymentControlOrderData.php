<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;

class PaymentControlOrderData
{
    public CustomerDataDto $personalData;

    public InvoiceAddressDto $invoiceAddress;

    public DeliveryAddressDto $deliveryAddress;

    public PaymentControlBasketDto $basket;
    
    public string $paymentMethodId;
}
