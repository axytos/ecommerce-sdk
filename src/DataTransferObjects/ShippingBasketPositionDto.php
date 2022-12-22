<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ShippingBasketPositionDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $productId;
    /**
     * @var int|null
     */
    public $quantity;
}
