<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

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
     * @var \Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto
     */
    public $orderPrecheckResponse;
}
