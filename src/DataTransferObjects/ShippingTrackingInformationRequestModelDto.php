<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ShippingTrackingInformationRequestModelDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $externalOrderId;
    /**
     * @var float|null
     */
    public $deliveryWeight;
    /**
     * @var string|null
     */
    public $trackingId;
    /**
     * @var string|null
     */
    public $logistician;
    /**
     * @var string|null
     */
    public $deliveryInformation;
    /**
     * @var DeliveryAddressDto|null
     */
    public $deliveryAddress;
}
