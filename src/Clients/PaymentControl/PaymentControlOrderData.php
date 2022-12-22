<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;

class PaymentControlOrderData
{
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\CustomerDataDto
     */
    public $personalData;

    /**
     * @var \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto
     */
    public $invoiceAddress;

    /**
     * @var \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto
     */
    public $deliveryAddress;

    /**
     * @var \Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto
     */
    public $basket;

    /**
     * @var string
     */
    public $paymentMethodId;
}
