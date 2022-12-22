<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlCheckRequestDto implements DtoInterface
{
    /**
     * @var string
     */
    public $requestMode;
    /**
     * @var string
     */
    public $proofOfInterest;
    /**
     * @var string
     */
    public $paymentTypeSecurity;
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
     * @var \Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto|null
     */
    public $paymentControlResponse;
}
