<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ReportShippingDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $externalOrderId;
    /**
     * @var ShippingBasketPositionDtoCollection|null
     */
    public $basketPositions;
}
