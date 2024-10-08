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
     * @var CustomerDataDto
     */
    public $personalData;
    /**
     * @var InvoiceAddressDto
     */
    public $invoiceAddress;
    /**
     * @var DeliveryAddressDto
     */
    public $deliveryAddress;
    /**
     * @var BasketDto
     */
    public $basket;
    /**
     * @var OrderPreCheckResponseDto|null
     */
    public $orderPrecheckResponse;
}
