<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ReturnPositionModelDto implements DtoInterface
{
    /**
     * @var int
     */
    public $quantityToReturn;
    /**
     * @var string
     */
    public $productId;
}
