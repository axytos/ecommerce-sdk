<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class OrderPreCheckRequestDto implements DtoInterface
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
     * @var string
     */
    public $selectedPaymentType;
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
     * @var \Axytos\ECommerce\DataTransferObjects\BasketDto
     */
    public $basket;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto|null
     */
    public $orderPrecheckResponse;
}
