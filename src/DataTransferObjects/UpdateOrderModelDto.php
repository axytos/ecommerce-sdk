<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class UpdateOrderModelDto implements DtoInterface
{
    /**
     * @var string
     */
    public $externalOrderId;

    /**
     * @var BasketDto
     */
    public $basket;
}
