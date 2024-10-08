<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class OrderCreateRequestDto implements DtoInterface
{
    /**
     * @var string
     */
    public $externalOrderId;
    /**
     * @var \DateTimeInterface
     */
    public $date;
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
     * @var OrderPreCheckResponseDto
     */
    public $orderPrecheckResponse;
}
